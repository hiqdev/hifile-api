<?php

namespace transmedia\signage\file\api\domain\file;

use DateTimeImmutable;
use hiapi\exceptions\domain\InvariantException;
use Ramsey\Uuid\Uuid;

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

    /** @var Uuid */
    private $id;

    /** @var int */
    private $client_id;

    /**
     * @var textS E.g. 'filestack'
     */
    private $provider;

    /**
     * @var text
     * Eg. FileStack ID: ttc9Vr9SjybPZ0W3Frys
     *      image png URL: https://cdn.filestackcontent.com/ttc9Vr9SjybPZ0W3Frys
     *      video mkv URL: https://cdn.filestackcontent.com/5aDSmbOWQiSiSHeP6KeR
     */
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

    /**
     * @var int|string (string for big integer)
     */
    private $size;

    /**
     * @var string
     */
    private $mimetype;

    /**
     * @var string
     */
    private $filename;

    /**
     * @var DateTimeImmutable
     */
    private $create_time;

    /**
     * @var DateTimeImmutable
     */
    private $update_time;

    /**
     * @var array
     */
    private $data = [];

    public function __construct($client_id, $provider, $remoteid)
    {
        $this->client_id = $client_id;
        $this->provider = $provider;
        $this->remoteid = $remoteid;
    }

    /**
     * @return int
     */
    public function getId(): ?Uuid
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getClientId(): ?int
    {
        return $this->client_id;
    }

    /**
     * @return string
     */
    public function getProvider(): string
    {
        return $this->provider;
    }

    /**
     * @return string
     */
    public function getRemoteId(): ?string
    {
        return $this->remoteid;
    }

    /**
     * @return string
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getDescr(): ?string
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
     * @return int|string
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @return string
     */
    public function getMimeType(): ?string
    {
        return $this->mimetype;
    }

    /**
     * @return string
     */
    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreateTime(): ?DateTimeImmutable
    {
        return $this->create_time;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getUpdateTime(): ?DateTimeImmutable
    {
        return $this->update_time;
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
    public function getData(): array
    {
        return $this->data;
    }

    public function setMetaData(array $data): void
    {
        foreach (['size','filename','mimetype'] as $key) {
            if (!empty($data[$key])) {
                $this->{$key} = $data[$key];
                unset($data[$key]);
            }
        }
        $this->data = array_merge($this->data ?? [], $data);
    }

}
