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

use hiqdev\yii\DataMapper\repositories\GenericRepositoryInterface;

/**
 * Interface FileRepositoryInterface.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
interface FileRepositoryInterface extends GenericRepositoryInterface
{
    /**
     * @param File $file
     * @return File
     */
    public function create(File $file): File;

    /**
     * @param File $file
     */
    public function delete(File $file): void;

    /**
     * @param File $file
     */
    public function persist(File $file): void;
}
