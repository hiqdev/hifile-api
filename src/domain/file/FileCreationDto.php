<?php
/**
 * HiFile file server API
 *
 * @link      https://github.com/hiqdev/hifile-api
 * @package   hifile-api
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\hifile\api\domain\file;

/**
 * Class FileCreationDto.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class FileCreationDto
{
    public $client_id;

    public $url;

    public $provider;

    public $remoteid;

    public $label;

    public $descr;

    public $type;

    public $state;
}
