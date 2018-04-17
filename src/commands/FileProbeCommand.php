<?php

namespace transmedia\signage\file\api\commands;

use hiapi\commands\BaseCommand;

/**
 * Class FileProbeCommand
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class FileProbeCommand extends BaseCommand
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
