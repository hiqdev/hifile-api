<?php

namespace transmedia\signage\file\api\domain;

use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Class FileEvent
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
abstract class AbstractEvent extends \League\Event\AbstractEvent implements \JsonSerializable
{
    /**
     * @var UuidInterface
     */
    private $uuid;
    /**
     * @var DateTimeImmutable
     */
    private $createdAt;
    /**
     * @var object
     */
    protected $target;

    /**
     * AbstractEvent constructor.
     * @param object $target
     */
    public function __construct($target = null)
    {
        $this->uuid = Uuid::uuid4();
        $this->createdAt = new DateTimeImmutable();
        $this->target = $target;
    }

    /**
     * @return object|null
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @inheritdoc
     */
    public function uuid(): UuidInterface
    {
        return $this->uuid;
    }

    /**
     * @inheritdoc
     */
    public function type(): string
    {
        return $this->getName();
    }

    /**
     * @inheritdoc
     */
    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return [
            'uuid' => $this->uuid(),
            'name' => $this->getName(),
            'createdAt' => $this->createdAt(),
        ];
    }
}