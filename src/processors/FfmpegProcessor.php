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

use Yii;

/**
 * class FfmpegProcessor.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class FfmpegProcessor implements ProcessorInterface
{
    protected $binDir;

    protected $version;

    protected $thumbMaker;

    public function __construct(string $binDir = '@root/ffmpeg', string $version = 'release', ThumbMakerInterface $thumbMaker)
    {
        $this->binDir = $this->prepareDir($binDir);
        $this->version = $version;
        $this->thumbMaker = $thumbMaker;
    }

    public function processFile(string $path): array
    {
        $lines = $this->ffmpeg(['-i', $path, '2>&1']);

        foreach ($lines as $line) {
            if (strpos($line, 'Duration') && preg_match('/Duration: (\d{2,4}):(\d{2}):(\d{2}\.\d+)/', $line, $matches)) {
                $duration = round(($matches[1]*60*60) + ($matches[2]*60) + ceil($matches[3]));
            }
            if (strpos($line, 'Video') && preg_match('/ (\d{3,5}x\d{3,5})/', $line, $matches)) {
                $resolution = $matches[1];
            }
        }

        $this->createThumbnail($path, $duration);

        return array_filter([
            'duration'      => $duration,
            'resolution'    => $resolution,
        ]);
    }

    public function createThumbnail(string $path, $duration): void
    {
        $frame = dirname($path) . '/frame.jpg';
        $thumb = dirname($path) . '/thumb.jpg';
        $position = (int) ($duration * (rand(30,80)/100));
        $this->ffmpeg(['-y', '-i', $path, '-vframes', 1, '-ss', $position, $frame]);
        $this->thumbMaker->make($frame, $thumb);
    }

    protected function ffmpeg($args): array
    {
        $this->install();

        return $this->exec($this->path('ffmpeg'), $args);
    }

    protected function install(): void
    {
        if (file_exists($this->path('ffmpeg'))) {
            return;
        }

        $url = "https://johnvansickle.com/ffmpeg/releases/ffmpeg-{$this->version}-64bit-static.tar.xz";
        $arc = $this->path('arc.tar.xz');
        $this->exec('/usr/bin/curl', [$url, '--output', $arc]);
        $this->exec('/bin/tar', ['xvf', $arc, '--strip', 1, '-C', $this->binDir]);

        if (!file_exists($this->path('ffmpeg'))) {
            throw new \Exception('failed install ffmpeg');
        }
    }

    protected function path($subpath): string
    {
        return $this->binDir . DIRECTORY_SEPARATOR . $subpath;
    }

    protected function prepareDir($dir)
    {
        $dir = Yii::getAlias($dir);
        if (!file_exists($dir)) {
            mkdir($dir);
        }

        return $dir;
    }

    protected function exec($prog, array $args): array
    {
        $skips = [
            '> /dev/null' => 1,
            '2>&1' => 1,
        ];
        $command = $prog;
        foreach ($args as $arg) {
            $arg = isset($skips[$arg]) ? $arg : escapeshellarg($arg);
            $command .= ' ' . $arg;
        }

        exec($command, $output);

        return $output;
    }
}
