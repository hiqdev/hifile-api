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

use claviska\SimpleImage;

/**
 * class SimpleThumbMaker.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class SimpleThumbMaker implements ThumbMakerInterface
{
    public $width;

    public $height;

    public function make(string $src, string $dst): void
    {
        $image = new SimpleImage($src);

        $image->resize($this->width)
            ->crop(0, 0, $this->width, $this->height)
            ->toFile($dst);
    }
}
