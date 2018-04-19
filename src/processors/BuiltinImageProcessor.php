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

namespace transmedia\signage\file\api\services\ThumbMakerInterface;

/**
 * class BuiltinImageProcessor
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

    public function collectInfo(string $path): array
    {
        [$width, $height] = getimagesize($path);

        $this->createThumbnail($path);

        return [
            'resolution' => "${width}x${height}",
        ];
    }

    public function createThumbnail(string $path): string
    {
        die(__METHOD__);
    }
}
