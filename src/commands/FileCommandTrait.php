<?php

namespace hiqdev\hifile\api\commands;

use hiqdev\hifile\api\domain\file\File;

/**
 * Trait FileCommandTrait
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
