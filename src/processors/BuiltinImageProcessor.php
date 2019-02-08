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
 * class BuiltinImageProcessor.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class BuiltinImageProcessor implements ProcessorInterface
{
    protected $thumbMaker;

    public function __construct(ThumbMakerInterface $thumbMaker)
    {
        $this->thumbMaker = $thumbMaker;
    }

    public function processFile(File $file, string $path): array
    {
        [$width, $height] = getimagesize($path);

        $this->createThumbnail($path);

        return [
            self::RESOLUTION => "${width}x${height}",
        ];
    }

    public function createThumbnail(string $path): void
    {
        $thumb = dirname($path) . '/' . self::THUMBFILE;
        $this->thumbMaker->make($path, $thumb);
    }
}
