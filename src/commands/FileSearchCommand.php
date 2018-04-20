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

use hiapi\commands\RequestAwareTrait;
use hiapi\commands\SearchCommand;

/**
 * Class FileSearchCommand.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class FileSearchCommand extends SearchCommand
{
    use RequestAwareTrait;
    use FileCommandTrait;
}
