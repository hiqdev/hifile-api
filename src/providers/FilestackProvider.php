<?php
/**
 * HiFile file server API
 *
 * @link      https://github.com/hiqdev/hifile-api
 * @package   hifile-api
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\hifile\api\providers;

use Filestack\FilestackClient;
use hiqdev\hifile\api\domain\file\File;
use hiqdev\hifile\api\domain\file\FileCreationDto;

/**
 * class FilestackProvider.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class FilestackProvider implements ProviderInterface
{
    protected static $domain = 'cdn.filestackcontent.com';
    /**
     * @var FilestackClient
     */
    protected        $filestack;

    public function __construct(FilestackClient $filestack)
    {
        $this->filestack = $filestack;
    }

    public function getMetaData($handle): array
    {
        return $this->filestack->getMetaData($handle, ['mimetype', 'size', 'filename']);
    }

    public function getRemoteUrl(File $file): string
    {
        return 'https://' . static::$domain . '/' . $file->getRemoteId();
    }

    public static function detect(FileCreationDto $dto): bool
    {
        $info = parse_url($dto->url);
        if (isset($info['host']) && $info['host'] === static::$domain) {
            $dto->remoteid = trim($info['path'], '/');

            return true;
        }

        return false;
    }
}
