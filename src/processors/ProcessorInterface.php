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

/**
 * ProcessorInterface.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
interface ProcessorInterface
{
    /**
     * @return array array with file information: resolution, duration if available
     */
    public function processFile(string $path): array;
}
