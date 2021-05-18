<?php
/**
 * HiFile file server API
 *
 * @link      https://github.com/hiqdev/hifile-api
 * @package   hifile-api
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\hifile\api\services;

use hiapi\event\EventStorageInterface;
use hiapi\exceptions\domain\InvariantException;
use hidev\helpers\Hidev;
use hiqdev\hifile\api\domain\file\File;
use hiqdev\hifile\api\domain\file\FileCreationDto;
use hiqdev\hifile\api\domain\file\FileFactoryInterface;
use hiqdev\hifile\api\domain\file\FileRepositoryInterface;
use hiqdev\hifile\api\domain\file\FileServiceInterface;
use hiqdev\hifile\api\domain\file\Url;
use hiqdev\hifile\api\processors\ProcessorFactoryInterface;
use hiqdev\hifile\api\providers\ProviderFactoryInterface;
use hiqdev\hifile\api\providers\ProviderInterface;
use hiqdev\yii\DataMapper\query\Specification;
use Ramsey\Uuid\Uuid;
use Yii;
use yii\helpers\FileHelper;
use yii\mutex\FileMutex;

/**
 * Class FileService.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class FileService implements FileServiceInterface
{
    /**
     * @var FileNotifierInterface
     */
    protected $notifier;
    /**
     * @var FileFactoryInterface
     */
    private $factory;

    /**
     * @var FileRepositoryInterface
     */
    private $repository;

    /**
     * @var ProviderFactoryInterface
     */
    private $providerFactory;

    /**
     * @var ProcessorFactoryInterface
     */
    private $processorFactory;

    /**
     * @var EventStorageInterface
     */
    private $eventStorage;

    public function __construct(
        FileFactoryInterface $fileFactory,
        FileRepositoryInterface $fileRepository,
        FileNotifierInterface $fileNotifier,
        ProviderFactoryInterface $providerFactory,
        ProcessorFactoryInterface $processorFactory,
        EventStorageInterface $eventStorage
    ) {
        $this->factory = $fileFactory;
        $this->repository = $fileRepository;
        $this->notifier = $fileNotifier;
        $this->providerFactory = $providerFactory;
        $this->processorFactory = $processorFactory;
        $this->eventStorage = $eventStorage;
    }

    public function create(FileCreationDto $dto): File
    {
        if (!$dto->provider && $dto->url) {
            $this->providerFactory->detect($dto);
        }
        $this->ensureRemoteIdIsUnique($dto->remoteid);
        $file = $this->factory->create($dto);
        $this->repository->create($file);
        $this->releaseEvents($file);

        return $file;
    }

    protected function releaseEvents(File $file): void
    {
        $this->eventStorage->store(...$file->releaseEvents());
    }

    protected function ensureRemoteIdIsUnique($remoteid): void
    {
        $spec = (new Specification())->where(['remoteid' => $remoteid]);
        $file = $this->repository->findOne($spec);
        if ($file) {
            throw new InvariantException('Given `remoteid` already exists');
        }
    }

    /**
     * @param int $id
     * @param string $type
     * @return File
     * @throws InvariantException
     */
    public function changeType(int $id, string $type): File
    {
        $file = $this->findOneOrFail($id);
        $file->setType($type);
        $this->repository->persist($file);

        return $file;
    }

    /**
     * @param int $id
     * @return File
     */
    public function delete($id): File
    {
        $file = $this->findOneOrFail($id);
        $this->repository->delete($file);

        return $file;
    }

    public function findOneOrFail($id): File
    {
        $uuid = Uuid::fromString($id);
        $spec = (new Specification())->where(['id' => $uuid->toString()]);

        return $this->repository->findOneOrFail($spec);
    }

    public function ensureMetadata(File $file): File
    {
        if (empty($file->getSize())) {
            $this->updateMetadata($file);
        }

        return $file;
    }

    public function updateMetadata(File $file): void
    {
        $handle = $file->getRemoteId();
        $provider = $this->getProvider($file);
        $metadata = $provider->getMetaData($handle);
        $this->setMetaData($file, $metadata);
    }

    public function setMetaData(File $file, array $metadata): void
    {
        $file->setMetaData($metadata);
        $this->persist($file);
    }

    public function persist(File $file): void
    {
        $this->repository->persist($file);
    }

    protected function getProvider(File $file): ProviderInterface
    {
        return $this->providerFactory->get($file->getProvider());
    }

    public function getRemoteUrl(File $file): string
    {
        return $this->getProvider($file)->getRemoteUrl($file);
    }

    public function getUrl(File $file): string
    {
        return $file->getUrl();
    }

    public function execFetch(File $file): void
    {
        Hidev::exec('file/fetch', [$file->getId()]);
    }

    public function execProbe(File $file): void
    {
        Hidev::exec('file/probe', [$file->getId()]);
    }

    public function getDestination(File $file): string
    {
        $dir = Yii::getAlias('@root/web/file/');

        return $dir . $this->getFilePath($file);
    }

    public function getFilePath(File $file, ?string $filename = null): string
    {
        $this->ensureMetadata($file);

        return Url::buildPathFromFile($file, $filename);
    }

    public function notify(File $file)
    {
        $this->notifier->notify($file);
    }

    public function probe(File $file): void
    {
        $dst = $this->getDestination($file);
        if (!file_exists($dst)) {
            $this->fetch($file);
        }

        $file->setMd5(md5_file($dst));

        $proc = $this->processorFactory->get($file);
        $info = $proc->processFile($file, $dst);
        $file->setReady();
        $this->setMetaData($file, $info);
        $this->releaseEvents($file);
    }

    public function fetch(File $file): void
    {
        $dst = $this->getDestination($file);
        if (file_exists($dst)) {
            return;
        }

        $dir = dirname($dst);
        FileHelper::createDirectory($dir, 0775, true);

        $mutex = new FileMutex();
        if (!$mutex->acquire($dst, 0)) {
            throw new \Exception('already working');
        }

        $this->fetchWithCurl($dst, $this->getRemoteUrl($file));
        $mutex->release($dst);

        if (!file_exists($dst)) {
            throw new \Exception(sprintf(
                'failed fetch file "%s" to destination "%s"',
                $file->getId(), $dst
            ));
        }
    }

    protected function fetchWithCurl($dst, $url): void
    {
        $fp = fopen($dst, 'w+');
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3600);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_exec($ch);
        if (curl_errno($ch)) {
            throw new \Exception(curl_error($ch));
        }
        curl_close($ch);
        fclose($fp);
    }

    protected function exec($prog, array $args): array
    {
        $skips = [
            '> /dev/null' => 1,
            '2>&1' => 1,
        ];
        $command = $prog;
        foreach ($args as $arg) {
            $arg = isset($skips[$arg]) ? $arg : escapeshellarg($arg);
            $command .= ' ' . $arg;
        }

        exec($command, $output);

        return $output;
    }
}
