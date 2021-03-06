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
 * ProcessorInterface.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
interface ProcessorInterface
{
    const THUMBFILE    = 'thumb.jpg';
    const DURATION     = 'duration';
    const DURATION_MS  = 'duration_ms';
    const RESOLUTION   = 'resolution';
    const MD5          = 'md5';
    const ALTERNATIVES = 'alternatives';

    /**
     * @param File $file that should be processed
     * @param string $path
     * @return array array with file information: resolution, duration if available
     */
    public function processFile(File $file, string $path): array;
}
