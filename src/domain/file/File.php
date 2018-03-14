<?php

namespace transmedia\signage\file\api\domain\file;

use hiapi\exceptions\domain\InvariantException;

/**
 * Class File
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class File
{
    const TYPE_NORMAL = 'normal';

    const STATE_NEW = 'new';
    const STATE_OK = 'ok';
    const STATE_DELETED = 'deleted';

    /** @var int */
    private $id;

    /** @var text */
    private $remoteid;

    /** @var string */
    private $label;

    /** @var string */
    private $descr;

    /**
     * @var string TODO: object
     */
    private $type;

    /**
     * @var string TODO: object
     */
    private $state;

    public function __construct($remoteid)
    {
        $this->login = $remoteid;
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getRemoteId(): string
    {
        return $this->remoteid;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getDescr(): string
    {
        return $this->descr;
    }

    /**
     * @return string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * @param string $type
     * @throws InvariantException when type is not valid
     */
    public function setType(string $type): void
    {
        $this->ensureTypeIsValid($type);
        $this->type = $type;
    }

    /**
     * @param string $type
     * @throws InvariantException when type is not valid
     */
    private function ensureTypeIsValid(string $type)
    {
        if (!in_array($type, [self::TYPE_NORMAL], true)) {
            throw new InvariantException('Invalid file type "' . $type . '"');
        }
    }

    /**
     * @param string $label
     */
    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    /**
     * @param string $descr
     */
    public function setDescr(string $descr): void
    {
        $this->descr = $descr;
    }
}
