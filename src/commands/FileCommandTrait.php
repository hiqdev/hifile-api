<?php
/**
 * HiFile file server API
 *
 * @link      https://github.com/hiqdev/hifile-api
 * @package   hifile-api
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\hifile\api\commands;

use hiqdev\hifile\api\domain\file\File;

/**
 * Trait FileCommandTrait.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
trait FileCommandTrait
{
    public function getEntityClass(): string
    {
        return File::class;
    }
}
