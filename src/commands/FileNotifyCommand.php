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

use hiapi\commands\BaseCommand;

/**
 * Class FileNotifyCommand.
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
