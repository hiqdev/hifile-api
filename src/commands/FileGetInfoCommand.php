<?php

namespace transmedia\signage\file\api\commands;

use hiapi\commands\RequestAwareTrait;
use hiapi\commands\GetInfoCommand;

/**
 * Class FileGetInfoCommand
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class FileGetInfoCommand extends GetInfoCommand
{
    use RequestAwareTrait;
    use FileCommandTrait;

    public function rules()
    {
        return [
            ['id', 'string'],
            ['id', 'required'],
        ];
    }
}
