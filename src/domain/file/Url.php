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
use Ramsey\Uuid\UuidInterface;
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

    /**
     * @param File|Uuid|string $id
     * @param string|null $filename
     * @return string
     */
    public static function build($id, string $filename = null): string
    {
        return rtrim(static::getSite(), '/') . '/file/' . static::buildPath($id, $filename);
    }

    /**
     * @param File|Uuid|string $id
     * @param string|null $filename
     * @return string
     */
    public static function buildPath($id, string $filename = null): string
    {
        if ($id instanceof File) {
            return static::buildPathFromFile($id, $filename);
        }
        if (!$id instanceof Uuid) {
            $id = Uuid::fromString($id);
        }

        return static::buildPathFromParts($id, $filename);
    }

    /**
     * @param File $file
     * @param string|null $filename
     * @return string
     */
    public static function buildPathFromFile(File $file, string $filename = null): string
    {
        return static::buildPathFromParts($file->getId(), $filename ?? $file->getFilename());
    }

    /**
     * @param Uuid $id
     * @param string|null $filename
     * @return string
     */
    public static function buildPathFromParts(Uuid $id, string $filename = null): string
    {
        $prefix = static::getPrefix($id);
        $filename = $filename ?: 'a';

        return "$prefix/$id/$filename";
    }

    /**
     * @param Uuid $id
     * @return string
     */
    public static function getPrefix(Uuid $id): string
    {
        return $id->getClockSeqLowHex();
    }

    public static function parse(string $url): string
    {
        throw new \Exception('not implemented');
    }
}
