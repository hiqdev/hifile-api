<?php

namespace transmedia\signage\file\api\services;

use hiqdev\yii\DataMapper\hydrator\GeneratedHydratorTrait;
use hiqdev\yii\DataMapper\hydrator\RootHydratorAwareTrait;
use Ramsey\Uuid\Uuid;
use Zend\Hydrator\HydrationInterface;
use Zend\Hydrator\HydratorInterface;

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

        return $this->generatedHydrate($data, $object);
    }

    /**
     * {@inheritdoc}
     * @param object|File $object
     */
    public function extract($object)
    {
        $result = array_filter([
            'id'        => $object->getId(),
            'client_id' => $object->getClientId(),
            'remoteid'  => $object->getRemoteId(),
            'type'      => $object->getType(),
            'state'     => $object->getState(),
            'label'     => $object->getLabel(),
            'descr'     => $object->getDescr(),
        ]);

        return $result;
    }
}
