<?php

namespace transmedia\signage\file\api\services;

use Ramsey\Uuid\Uuid;
use hiqdev\yii\DataMapper\query\Specification;
use transmedia\signage\file\api\domain\file\File;
use transmedia\signage\file\api\domain\file\FileFactoryInterface;
use transmedia\signage\file\api\domain\file\FileRepositoryInterface;
use transmedia\signage\file\api\domain\file\FileServiceInterface;
use transmedia\signage\file\api\domain\file\FileCreationDto;
use transmedia\signage\file\api\providers\ProviderInterface;
use transmedia\signage\file\api\providers\ProviderFactoryInterface;
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

    public function __construct(
        FileFactoryInterface $fileFactory,
        FileRepositoryInterface $fileRepository,
        ProviderFactoryInterface $providerFactory
    ) {
        $this->repository = $fileRepository;
        $this->factory = $fileFactory;
        $this->providerFactory = $providerFactory;
    }

    public function create(FileCreationDto $dto): File
    {
        if (!$dto->provider && $dto->url) {
            $this->providerFactory->detect($dto);
        }
        $file = $this->factory->create($dto);
        $this->repository->create($file);

        return $file;
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
        $file->setMetaData($metadata);
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

    public function fetchFile(File $file): void
    {
        $this->runHidev('file/fetch', [$file->getId()]);
    }

    public function probeFile(File $file): void
    {
        $this->runHidev('file/probe', [$file->getId()]);
    }

    protected function runHidev($route, array $args, $wait = false): void
    {
        $command = Yii::getAlias('@vendor/bin/hidev') . ' ' . $route;
        foreach ($args as $arg) {
            $command .= ' ' . escapeshellarg($arg);
        }
        $amp = $wait ? '' : '&';
        exec("$command > /dev/null 2>&1 $amp");
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
}
