<?php

namespace transmedia\signage\file\api\commands;

use hiapi\commands\RequestAwareTrait;
use hiapi\commands\SearchCommand;
use transmedia\signage\file\api\domain\file\File;

/**
 * Class FileSearchCommand
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class FileSearchCommand extends SearchCommand
{
    use RequestAwareTrait;

    public function getEntityClass()
    {
        return File::class;
    }
}
