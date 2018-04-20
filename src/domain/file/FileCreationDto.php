<?php

namespace hiqdev\hifile\api\domain\file;

/**
 * Class FileCreationDto
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
