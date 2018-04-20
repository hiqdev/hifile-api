<?php
/**
 * HiFile file server API
 *
 * @link      https://github.com/hiqdev/hifile-api
 * @package   hifile-api
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\hifile\api\services;

use hiapi\event\PublishToQueueListener;
use hiqdev\hifile\api\domain\file\events\FileNotification;
use hiqdev\hifile\api\domain\file\File;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Psr\Log\LoggerInterface;

/**
 * Class NotifyToQueue.
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
