<?php

namespace transmedia\signage\file\api\services;

use hiqdev\yii\DataMapper\factories\HydratorAwareFactoryTrait;
use transmedia\signage\file\api\domain\file\FileFactoryInterface;
use transmedia\signage\file\api\domain\file\File;
use transmedia\signage\file\api\domain\file\FileCreationDto;
use Zend\Hydrator\HydratorInterface;

class FileFactory implements FileFactoryInterface
{
    use HydratorAwareFactoryTrait;

    /**
     * @var HydratorInterface
     */
    private $hydrator;

    public function __construct(HydratorInterface $hydrator)
    {
        $this->hydrator = $hydrator;
    }

    public function getEntityClassName(): string
    {
        return File::class;
    }

    /**
     * @param FileCreationDto $dto
     * @return File
     */
    public function create(FileCreationDto $dto): File
    {
        return new File($dto->login, $dto->password);
    }
}
