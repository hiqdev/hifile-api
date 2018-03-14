<?php

namespace transmedia\hiapi\modules\file;

use hiapi\exceptions\domain\InvariantException;
use hiqdev\yii\DataMapper\query\Specification;
use transmedia\signage\file\api\domain\file\aggregates\File;
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
     * @var string
     */
    private $defaultSeller;
    /**
     * @var FileFactoryInterface
     */
    private $factory;

    public function __construct($defaultSeller, FileRepositoryInterface $fileRepository, FileFactoryInterface $fileFactory)
    {
        $this->repository = $fileRepository;
        $this->defaultSeller = $defaultSeller;
        $this->factory = $fileFactory;
    }

    public function create(FileCreationDto $dto): File
    {
        $file = new File($dto->login, $dto->password);
        if ($dto->type !== null) {
            $file->setType($dto->type);
        }

        $this->factory->hydrate(['seller' => ['login' => $dto->seller]], $file);

        $this->ensureLoginIsUnique($file);
        $this->repository->create($file);

        return $this->repository->findOneOrFail((new Specification())->where(['id' => $file->getId()]));
    }

    /**
     * @param int $id
     * @param string $type
     * @return File
     */
    public function changeType(int $id, string $type): File
    {
        $spec = (new Specification())->where(['id' => $id]);
        $file = $this->repository->findOneOrFail($spec);

        $file->setType($type);
        $this->repository->persist($file);

        return $this->repository->findOneOrFail($spec);
    }

    /**
     * @param int $id
     * @return File
     */
    public function delete($id): File
    {
        $file = $this->repository->findOneOrFail((new Specification())->where(['id' => $id]));

        $this->repository->delete($file);

        return $file;
    }
}
