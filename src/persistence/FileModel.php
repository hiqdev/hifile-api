<?php

namespace transmedia\signage\file\api\persistence;

use hiqdev\yii\DataMapper\models\AbstractModel;
use hiqdev\yii\DataMapper\query\attributes\IntegerAttribute;
use hiqdev\yii\DataMapper\query\attributes\StringAttribute;
use hiqdev\yii\DataMapper\query\attributes\UuidAttribute;

class FileModel extends AbstractModel
{
    public function attributes()
    {
        return [
            'id'        => UuidAttribute::class,
            'remoteid'  => StringAttribute::class,
            'client_id' => IntegerAttribute::class,
            'type'      => StringAttribute::class,
            'state'     => StringAttribute::class,
            'type_id'   => IntegerAttribute::class,
            'state_id'  => IntegerAttribute::class,
        ];
    }

    public function relations()
    {
        return [];
    }
}
