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
    public $remoteid;

    /**
     * @var string
     */
    public $label;

    /**
     * @var string
     */
    public $descr;

    /**
     * @var string
     */
    public $type;

    public function rules()
    {
        return [
            [['remoteid'], 'string'],
            [['type'], RefValidator::class],
            [['label'], 'string'],
            [['descr'], 'string'],
        ];
    }
}
