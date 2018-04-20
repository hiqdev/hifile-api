<?php
/**
 * HiFile file server API
 *
 * @link      https://github.com/hiqdev/hifile-api
 * @package   hifile-api
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\hifile\api\processors;

use hiqdev\hifile\api\domain\file\File;

/**
 * ProcessorFactoryInterface.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
interface ProcessorFactoryInterface
{
    public function get(File $file): ProcessorInterface;
}
