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

use claviska\SimpleImage;

/**
 * class SimpleThumbMaker
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

        $image->resize($width)
            ->crop(0, 0, $width, $height)
            ->toFile($dst);
    }
}
