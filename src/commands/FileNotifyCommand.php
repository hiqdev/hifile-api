<?php

namespace transmedia\signage\file\api\commands;

use hiapi\commands\BaseCommand;

/**
 * Class FileNotifyCommand
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class FileNotifyCommand extends BaseCommand
{
    /**
     * @var string
     */
    public $id;

    public function rules()
    {
        return [
            ['id', 'string'],
            ['id', 'required'],
        ];
    }
}
