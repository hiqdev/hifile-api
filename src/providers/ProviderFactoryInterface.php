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

use hiqdev\hifile\api\domain\file\FileCreationDto;

/**
 * ProviderFactoryInterface.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
interface ProviderFactoryInterface
{
    public function detect(FileCreationDto $dto): void;

    public function get(string $id): ProviderInterface;
}
