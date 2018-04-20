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

use Zend\Hydrator\HydrationInterface;

/**
 * Interface FileFactoryInterface.
 */
interface FileFactoryInterface extends HydrationInterface
{
    /**
     * @param FileCreationDto $dto
     * @return File
     */
    public function create(FileCreationDto $dto): File;
}
