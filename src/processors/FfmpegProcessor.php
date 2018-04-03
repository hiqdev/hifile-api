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
 * class FfmpegProcessor
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class FfmpegProcessor implements ProcessorInterface
{
    public function collectInfo(string $path): array
    {
        die(__METHOD__);
        $lines = $this->exec('/usrlocal/bin/ffmpeg', ['-i', $dst, '2>&1']);

        foreach ($lines as $line) {
            if (strpos($line, 'Duration') && preg_match('/Duration: (\d{2,4}):(\d{2}):(\d{2})/', $line, $matches)) {
                $duration = round(($matches[1]*60*60) + ($matches[2]*60) + $matches[3]);
            }
        }

        return false;
    }

    public function createThumbnail(string $path): string
    {
        die(__METHOD__);
    }
}
