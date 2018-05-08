<?php
/**
 * HiFile file server API
 *
 * @link      https://github.com/hiqdev/hifile-api
 * @package   hifile-api
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\hifile\api\domain\file;

use Ramsey\Uuid\Uuid;
use Yii;

/**
 * Class Url.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class Url
{
    public static function getSite(): string
    {
        return Yii::$app->params['file.site'];
    }

    public static function build($id, string $filename = null): string
    {
        return rtrim(static::getSite(), '/') . '/file/' . static::buildPath($id, $filename);
    }

    public static function buildPath($id, string $filename = null): string
    {
        if ($id instanceof File) {
            return static::buildPathFromFile($id);
        }
        if (!$id instanceof Uuid) {
            $id = Uuid::fromString($id);
        }

        return static::buildPathFromParts($id, $filename);
    }

    public static function buildPathFromFile(File $file): string
    {
        return static::buildPathFromParts($file->getId(), $file->getFilename() ?: 'a');
    }

    public static function buildPathFromParts(Uuid $id, string $filename = null): string
    {
        $prefix = static::getPrefix($id);
        $filename = $filename ?: 'a';

        return "$prefix/$id/$filename";
    }

    public static function getPrefix(Uuid $id): string
    {
        return $id->getClockSeqLowHex();
    }

    public static function parse(string $url): string
    {
        throw new \Exception('not implemented');
    }
}
