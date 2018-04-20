<?php

namespace hiqdev\hifile\api\domain\file;

use Ramsey\Uuid\Uuid;
use Yii;

/**
 * Class Url
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class Url
{
    public static function getSite(): string
    {
        return Yii::$app->params['file.site'];
    }

    public static function build($id): string
    {
        return rtrim(static::getSite(), '/') . '/file/' . static::buildPath($id);
    }

    public static function buildPath($id): string
    {
        if ($id instanceof File) {
            return static::buildPathFromFile($id);
        }
        if (!$id instanceof Uuid) {
            $id = Uuid::fromString($id);
        }

        return static::buildPathFromParts($id, 'a');
    }

    public static function buildPathFromFile(File $file): string
    {
        return static::buildPathFromParts($file->getId(), $file->getFilename() ?: 'a');
    }

    public static function buildPathFromParts(Uuid $id, string $filename): string
    {
        $prefix = static::getPrefix($id);

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
