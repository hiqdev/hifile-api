<?php

namespace transmedia\signage\file\api\services;

use Ramsey\Uuid\Uuid;
use hiapi\event\EventStorageInterface;
use hiapi\exceptions\domain\InvariantException;
use hidev\helpers\Hidev;
use hiqdev\yii\DataMapper\query\Specification;
use transmedia\signage\file\api\domain\file\File;
use transmedia\signage\file\api\domain\file\FileFactoryInterface;
use transmedia\signage\file\api\domain\file\FileRepositoryInterface;
use transmedia\signage\file\api\domain\file\FileServiceInterface;
use transmedia\signage\file\api\domain\file\FileCreationDto;
use transmedia\signage\file\api\providers\ProviderInterface;
use transmedia\signage\file\api\providers\ProviderFactoryInterface;
use transmedia\signage\file\api\processors\ProcessorFactoryInterface;
use Yii;

/**
 * Class FileService
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class FileService implements FileServiceInterface
{
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
        ProviderFactoryInterface $providerFactory,
        ProcessorFactoryInterface $processorFactory,
        EventStorageInterface $eventStorage
    ) {
        $this->repository = $fileRepository;
        $this->factory = $fileFactory;
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
        $this->eventStorage->store(...$file->releaseEvents());

        return $file;
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

    public function findOneOrFail($id)
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
        return '/file/' . $this->getFilePath($file);
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

    public function getFilePath(File $file): string
    {
        $this->ensureMetadata($file);
        $prefix = $this->getPrefix($file);
        $id = $file->getId();
        $filename = $file->getFilename();

        return "$prefix/$id/$filename";
    }

    public function getPrefix(File $file): string
    {
        return $file->getId()->getClockSeqLowHex();
    }

    public function probe(File $file)
    {
        $dst = $this->getDestination($file);
        if (!file_exists($dst)) {
            $this->fetch($file);
        }

        $proc = $this->processorFactory->get($file);
        $info = $proc->collectInfo($dst);
        $this->setMetaData($file, $info);
    }

    public function fetch(File $file): void
    {
        $dst = $this->getDestination($file);
        if (file_exists($dst)) {
            return;
        }

        $dir = dirname($dst);
        if (!file_exists($dir)) {
            exec("mkdir -p $dir");
        }
        $lock = fopen("$dir/.fetching", "c");

        if (!$lock || !flock($lock, LOCK_EX | LOCK_NB)) {
            throw new \Exception('already working');
        }

        $url = $this->getRemoteUrl($file);
        $this->exec('/usr/bin/curl', ['-o', $dst, $url]);
        if (!file_exists($dst)) {
            throw new \Exception("failed fetch file: {$file->getId()}");
        }
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
