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
 * ThumbMakerInterface
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
interface ThumbMakerInterface
{
    /**
     * @param string $src source path
     * @param string $dst destination path
     */
    public function make(string $src, string $dst): void;
}
