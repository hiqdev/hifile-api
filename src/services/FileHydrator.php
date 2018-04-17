<?php

namespace transmedia\signage\file\api\services;

use DateTime;
use DateTimeImmutable;
use hiqdev\yii\DataMapper\hydrator\GeneratedHydratorTrait;
use hiqdev\yii\DataMapper\hydrator\RootHydratorAwareTrait;
use Ramsey\Uuid\Uuid;
use Zend\Hydrator\HydrationInterface;
use Zend\Hydrator\HydratorInterface;
use yii\helpers\Json;

/**
 * Class FileHydrator
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class FileHydrator implements HydratorInterface
{
    use RootHydratorAwareTrait;
    use GeneratedHydratorTrait {
        hydrate as generatedHydrate;
    }

    /**
     * {@inheritdoc}
     * @param object|File $object
     */
    public function hydrate(array $data, $object)
    {
        if (!empty($data['id'])) {
            $data['id'] = Uuid::fromString($data['id']);
        }
        if (!empty($data['data'])) {
            $data['data'] = Json::decode($data['data']);
        }
        if (!empty($data['create_time'])) {
            $data['create_time'] = new DateTimeImmutable($data['create_time']);
        }
        if (!empty($data['update_time'])) {
            $data['update_time'] = new DateTimeImmutable($data['update_time']);
        }

        return $this->generatedHydrate($data, $object);
    }

    /**
     * {@inheritdoc}
     * @param object|File $object
     */
    public function extract($object)
    {
        $result = array_filter([
            'id'            => $object->getId()->toString(),
            'url'           => $object->getUrl(),
            'client_id'     => $object->getClientId(),
            'provider'      => $object->getProvider(),
            'remoteid'      => $object->getRemoteId(),
            'mimetype'      => $object->getMimeType(),
            'filename'      => $object->getFilename(),
            'size'          => $object->getSize(),
            'type'          => $object->getType(),
            'state'         => $object->getState(),
            'label'         => $object->getLabel(),
            'descr'         => $object->getDescr(),
            'data'          => $object->getData(),
            'create_time'   => $this->time2iso($object->getCreateTime()),
            'update_time'   => $this->time2iso($object->getUpdateTime()),
        ]);

        return $result;
    }

    protected function time2iso(DateTimeImmutable $time = null)
    {
        return $time ? $time->format(DateTime::ATOM) : null;
    }
}
