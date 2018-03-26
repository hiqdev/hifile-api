<?php

namespace transmedia\signage\file\api\commands;

use transmedia\signage\file\api\domain\file\File;

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
