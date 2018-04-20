<?php

namespace hiqdev\hifile\api\commands;

use hiapi\commands\BaseCommand;

/**
 * Class FileFetchCommand
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class FileFetchCommand extends BaseCommand
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
