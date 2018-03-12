<?php

namespace transmedia\signage\file\api\commands;

use hiapi\commands\BaseCommand;
use hiapi\validators\RefValidator;

/**
 * Class FileCreateCommand
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class FileCreateCommand extends BaseCommand
{
    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $label;

    /**
     * @var string
     */
    public $descr;

    public function rules()
    {
        return [
            [['type'], RefValidator::class],
            [['label'], 'string'],
            [['descr'], 'string'],
        ];
    }
}
