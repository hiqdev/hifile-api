<?php

namespace transmedia\signage\file\api\services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use Psr\Log\LoggerInterface;
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
    public function __construct(AMQPStreamConnection $amqp, LoggerInterface $logger, FileHydrator $hydrator)
    {
        parent::__construct($amqp, $logger);
        $this->hydrator = $hydrator;
    }

    /**
     * @param File $file
     */
    public function notify(File $file): void
    {
        $this->handle(new FileNotification($this->hydrator->extract($file)));
    }
}