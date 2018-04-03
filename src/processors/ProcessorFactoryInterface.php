<?php
/**
 * File API
 *
 * @link      https://github.com/transmedia/filer-api
 * @package   filer-api
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2018, HiQDev (http://hiqdev.com/)
 */

namespace transmedia\signage\file\api\processors;

use transmedia\signage\file\api\domain\file\File;

/**
 * ProcessorFactoryInterface
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
interface ProcessorFactoryInterface
{
    public function get(File $file): ProcessorInterface;
}
