<?php

namespace transmedia\signage\file\api\domain\file;

use Zend\Hydrator\HydrationInterface;

/**
 * Interface FileFactoryInterface
 */
interface FileFactoryInterface extends HydrationInterface
{
    /**
     * @param FileCreationDto $dto
     * @return File
     */
    public function create(FileCreationDto $dto): File;
}
