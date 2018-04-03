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

/**
 * ProcessorInterface
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
interface ProcessorInterface
{
    public function collectInfo(string $path): array;

    public function createThumbnail(string $path): string;
}
