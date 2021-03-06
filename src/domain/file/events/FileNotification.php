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

/**
 * Class FileNotification.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
final class FileNotification extends AbstractEvent
{
    /**
     * @var array
     */
    protected $target;

    /**
     * AbstractEvent constructor.
     * @param object $target
     */
    public function __construct(array $target)
    {
        parent::__construct($target);
    }

    public function jsonSerialize()
    {
        return array_merge(parent::jsonSerialize(), $this->target);
    }
}
