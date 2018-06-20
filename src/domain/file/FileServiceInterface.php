<?php
/**
 * HiFile file server API
 *
 * @link      https://github.com/hiqdev/hifile-api
 * @package   hifile-api
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\hifile\api\domain\file;

/**
 * Interface FileRepositoryInterface.
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

    /**
     * @param File $file
     * @return File
     */
    public function ensureMetadata(File $file): File;

    /**
     * @param $file
     * @return mixed
     */
    public function probe(File $file): void;
}
