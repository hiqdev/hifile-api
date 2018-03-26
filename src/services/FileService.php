<?php

namespace transmedia\signage\file\api\services;

use hiqdev\yii\DataMapper\query\Specification;
use transmedia\signage\file\api\domain\file\File;
use transmedia\signage\file\api\domain\file\FileFactoryInterface;
use transmedia\signage\file\api\domain\file\FileRepositoryInterface;
use transmedia\signage\file\api\domain\file\FileServiceInterface;
use transmedia\signage\file\api\domain\file\FileCreationDto;
use transmedia\signage\file\api\providers\ProviderFactoryInterface;

/**
 * Class FileService
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class FileService implements FileServiceInterface
{
    /**
     * @var FileRepositoryInterface
     */
    private $repository;
    /**
     * @var FileFactoryInterface
     */
    private $factory;

    public function __construct(
        FileRepositoryInterface $fileRepository,
        FileFactoryInterface $fileFactory,
        ProviderFactoryInterface $providerFactory
    ) {
        $this->repository = $fileRepository;
        $this->factory = $fileFactory;
        $this->providerFactory = $providerFactory;
    }

    public function create(FileCreationDto $dto): File
    {
        if (!$dto->provider && $dto->url) {
            $this->detectProvider($dto);
        }
        $file = $this->factory->create($dto);
        $this->repository->create($file);

        return $file;
    }

    protected function detectProvider(FileCreationDto $dto)
    {
        $info = parse_url($dto->url);
        if (isset($info['host']) && $info['host'] === 'cdn.filestackcontent.com') {
            $dto->provider = 'filestack';
            $dto->remoteid = trim($info['path'], '/');
            return;
        }

        throw new \Exception('cannot detect file provider');
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
        $spec = (new Specification())->where(['id' => $id]);

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
        $provider = $this->providerFactory->get($file->getProvider());
        $handle = $file->getRemoteId();
        $metadata = $provider->getMetaData($handle);
        $file->setMetaData($metadata);
        $this->repository->persist($file);
    }
}
