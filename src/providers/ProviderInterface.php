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

use hiqdev\hifile\api\domain\file\File;
use hiqdev\hifile\api\domain\file\FileCreationDto;

/**
 * ProviderInterface.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
interface ProviderInterface
{
    public function getMetaData($id): array;

    public function getRemoteUrl(File $file): string;

    public static function detect(FileCreationDto $dto): bool;
}
