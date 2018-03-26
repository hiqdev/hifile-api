<?php

namespace transmedia\signage\file\api\commands;

use hiapi\commands\RequestAwareTrait;
use hiapi\commands\SearchCommand;

/**
 * Class FileSearchCommand
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class FileSearchCommand extends SearchCommand
{
    use RequestAwareTrait;
    use FileCommandTrait;
}
