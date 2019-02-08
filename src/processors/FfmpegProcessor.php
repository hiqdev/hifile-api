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

    /**
     * @var ThumbMakerInterface
     */
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
            if (strpos($line, 'Duration') && preg_match('/Duration: (\d{2,4}):(\d{2}):(\d{2})\.(\d+)/', $line, $matches)) {
                $duration_ms = round(($matches[1]*60*60*1000) + ($matches[2]*60*1000) + $matches[3]*1000 + $matches[4]);
            }
            if (strpos($line, 'Video') && preg_match('/ (\d{3,5}x\d{3,5})/', $line, $matches)) {
                $resolution = $matches[1];
            }
        }

        $this->createThumbnail($path, $duration);
        $this->convertX264($path, $x264 = '');
        $this->convertX265($path, $x265 = '');

        return array_filter([
            self::DURATION      => $duration,
            self::DURATION_MS   => $duration_ms ?? null,
            self::RESOLUTION    => $resolution ?? null,
            self::ALTERNATIVES  => [
                'web' => $x264,
                'player' => $x265
            ]
        ]);
    }

    public function createThumbnail(string $path, $duration): void
    {
        $frame = \dirname($path) . '/frame.jpg';
        $thumb = \dirname($path) . '/' . self::THUMBFILE;
        $position = (int) ($duration * (rand(30,80)/100));
        $this->ffmpeg(['-y', '-i', $path, '-vframes', 1, '-ss', $position, $frame]);
        $this->thumbMaker->make($frame, $thumb);
    }

    private function convertX265(string $source, string &$target): void
    {
        $target = \dirname($source) . '/converted_x265.' . pathinfo($source, PATHINFO_FILENAME) . '.mp4';

        $this->ffmpeg([
            '-y', '-i', $source,
            '-threads', '2', '-map_metadata', '-1', '-loglevel', 'error',
            '-filter_complex', '[0]trim=0:0.5[hold];[0][hold]concat[extended];[extended][0]overlay',
            '-r', 'ntsc', '-c:v', 'libx265', '-preset', 'medium', '-crf', '22',
            '-maxrate', '5M', '-bufsize', '2M', '-pix_fmt', 'yuv420p',
            '-movflags', 'faststart', '-x265-params', 'keyint=30:min-keyint=0:scenecut=0',
            '-c:a', 'libmp3lame', '-ac', '2', '-ar', '48000', '-b:a', '160k',
            '-f', 'mpegts', '-mpegts_service_type', '0x1F',
            $target
       ]);
    }

    private function convertX264(string $source, string &$target): void
    {
        $target = \dirname($source) . '/converted_x264.' . pathinfo($source, PATHINFO_FILENAME) . '.mp4';

        $this->ffmpeg([
            '-y', '-i', $source,
            '-preset', 'medium',
            '-maxrate', '5M', '-bufsize', '2M',
            '-b:v', '5M', '-bt:v', '5M', '-c:v', 'libx264',
            '-c:a', 'libmp3lame', '-ac', '2', '-ar', '48000', '-b:a', '160k',
            $target
        ]);
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
        setlocale(LC_CTYPE, "C.UTF-8");
        foreach ($args as $arg) {
            $arg = isset($skips[$arg]) ? $arg : escapeshellarg($arg);
            $command .= ' ' . $arg;
        }

        exec($command, $output);

        return $output;
    }
}
