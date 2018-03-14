<?php

namespace transmedia\signage\file\api\domain\file;

use transmedia\signage\file\api\domain\file\File;
use transmedia\signage\file\api\domain\file\FileCreationDto;

/**
 * Interface FileRepositoryInterface
 */
interface FileServiceInterface
{
    /**
     * @param FileCreationDto $dto
     * @return File
     */
    public function create(FileCreationDto $dto): File;

    /**
     * @param int $id
     * @param string $type
     * @return File
     */
    public function changeType(int $id, string $type): File;

    /**
     * @param int $id
     * @return File
     */
    public function delete($id): File;
}
