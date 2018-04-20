<?php
/**
 * HiFile file server API
 *
 * @link      https://github.com/hiqdev/hifile-api
 * @package   hifile-api
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\hifile\api\domain\file\events;

use hiqdev\hifile\api\domain\AbstractEvent;
use hiqdev\hifile\api\domain\file\File;

/**
 * Class FileEvent.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class FileEvent extends AbstractEvent
{
    /**
     * @var File
     */
    protected $target;

    /**
     * AbstractEvent constructor.
     * @param object $target
     */
    public function __construct(File $target = null)
    {
        parent::__construct($target);
    }

    public function jsonSerialize()
    {
        return array_merge(parent::jsonSerialize(), [
            'id'        => $this->target->getId(),
            'client_id' => $this->target->getClientId(),
            'provider'  => $this->target->getProvider(),
            'remoteid'  => $this->target->getRemoteId(),
        ]);
    }
}
