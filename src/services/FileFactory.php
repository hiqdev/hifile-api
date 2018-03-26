<?php

namespace transmedia\signage\file\api\services;

use hiqdev\yii\DataMapper\factories\HydratorAwareFactoryTrait;
use transmedia\signage\file\api\domain\file\File;
use transmedia\signage\file\api\domain\file\FileCreationDto;
use transmedia\signage\file\api\domain\file\FileFactoryInterface;
use Zend\Hydrator\HydratorInterface;

/**
 * Class FileFactory
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
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
        $file = new File($dto->client_id, $dto->provider, $dto->remoteid);
        if ($dto->label !== null) {
            $file->setLabel($dto->label);
        }
        if ($dto->descr !== null) {
            $file->setDescr($dto->descr);
        }
        if ($dto->type !== null) {
            $file->setType($dto->type);
        }

        return $file;
    }
}
