<?php

namespace transmedia\signage\file\api\services;

use hiapi\event\PublishToQueueListener;
use transmedia\signage\file\api\domain\file\File;
use transmedia\signage\file\api\domain\file\events\FileNotification;

/**
 * Class NotifyToQueue
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class NotifyToQueue extends PublishToQueueListener implements FileNotifierInterface
{
    /**
     * @param File $file
     */
    public function notify(File $file): void
    {
        $this->handle(new FileNotification($file));
    }
}
