<?php
/**
 * HiFile file server API
 *
 * @link      https://github.com/hiqdev/hifile-api
 * @package   hifile-api
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\hifile\api\domain\file;

use DateTimeImmutable;
use hiapi\exceptions\domain\InvariantException;
use hiqdev\hifile\api\domain\file\events\FileGotReady;
use hiqdev\hifile\api\domain\file\events\FileWasCreated;
use hiqdev\hifile\api\processors\ProcessorInterface;
use League\Event\GeneratorTrait;
use Ramsey\Uuid\Uuid;

/**
 * Class File.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class File
{
    use GeneratorTrait;

    const TYPE_NORMAL = 'normal';

    const STATE_NEW = 'new';
    const STATE_READY = 'ready';
    const STATE_DELETED = 'deleted';

    /** @var Uuid */
    private $id;

    /** @var int */
    private $client_id;

    /**
     * @var string E.g. 'filestack'
     */
    private $provider;

    /**
     * @var string
     * Eg. FileStack ID: ttc9Vr9SjybPZ0W3Frys
     *       image png URL: https://cdn.filestackcontent.com/ttc9Vr9SjybPZ0W3Frys
     * short video mkv URL: https://cdn.filestackcontent.com/5aDSmbOWQiSiSHeP6KeR
     *  long video mkv URL: https://cdn.filestackcontent.com/QZf5NbRBmYAGtaKbA1oG
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
     * @var string
     */
    private $md5;

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
        $this->state = self::STATE_NEW;
        $this->addEvent(new FileWasCreated($this));
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
        return $this->state ?: self::STATE_NEW;
    }

    public function setReady(): void
    {
        $this->state = self::STATE_READY;
        $this->addEvent(new FileGotReady($this));
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
     * @return string
     */
    public function getUrl(): string
    {
        return Url::build($this);
    }

    public function getThumbUrl(): string
    {
        return Url::build($this, ProcessorInterface::THUMBFILE);
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
        $data = $this->data ?? [];
        if (isset($data[ProcessorInterface::RESOLUTION])) {
            $data['thumbUrl'] = $this->getThumbUrl();
        }

        return $data;
    }

    public function setMetaData(array $data): void
    {
        foreach (['size', 'filename', 'mimetype', 'md5'] as $key) {
            if (!empty($data[$key])) {
                $this->{$key} = $data[$key];
                unset($data[$key]);
            }
        }
        $this->data = array_merge($this->data ?? [], $data);
    }

    /**
     * @return string|null
     */
    public function getMd5(): ?string
    {
        return $this->md5;
    }

    /**
     * @param string $md5
     */
    public function setMd5(string $md5): void
    {
        $this->md5 = $md5;
    }
}
