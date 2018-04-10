<?php

namespace transmedia\signage\file\api\domain\file\events;

use transmedia\signage\file\api\domain\AbstractEvent;
use transmedia\signage\file\api\domain\file\File;

/**
 * Class FileEvent
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
