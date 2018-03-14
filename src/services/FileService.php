<?php

namespace transmedia\signage\file\api\services;

use hiapi\exceptions\domain\InvariantException;
use hiqdev\yii\DataMapper\query\Specification;
use transmedia\signage\file\api\domain\file\File;
use transmedia\signage\file\api\domain\file\FileFactoryInterface;
use transmedia\signage\file\api\domain\file\FileRepositoryInterface;
use transmedia\signage\file\api\domain\file\FileServiceInterface;
use transmedia\signage\file\api\domain\file\FileCreationDto;

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

    public function __construct(FileRepositoryInterface $fileRepository, FileFactoryInterface $fileFactory)
    {
        $this->repository = $fileRepository;
        $this->factory = $fileFactory;
    }

    public function create(FileCreationDto $dto): File
    {
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
        $spec = (new Specification())->where(['id' => $id]);

        return $this->repository->findOneOrFail($spec);
    }
}
