<?php
/**
 * HiFile file server API
 *
 * @link      https://github.com/hiqdev/hifile-api
 * @package   hifile-api
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\hifile\api\services;

use hiqdev\hifile\api\domain\file\File;

/**
 * Interface FileNotifierInterface.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
interface FileNotifierInterface
{
    /**
     * @param File $file
     */
    public function notify(File $file): void;
}
