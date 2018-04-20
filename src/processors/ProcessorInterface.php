<?php
/**
 * File API
 *
 * @link      https://github.com/transmedia/filer-api
 * @package   filer-api
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\hifile\api\processors;

/**
 * ProcessorInterface
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
