<?php

namespace transmedia\signage\file\api\persistence;

use hiqdev\yii\DataMapper\models\AbstractModel;
use hiqdev\yii\DataMapper\query\attributes\IntegerAttribute;
use hiqdev\yii\DataMapper\query\attributes\StringAttribute;

class FileModel extends AbstractModel
{
    public function attributes()
    {
        return [
            'id' => IntegerAttribute::class,
            'remoteid' => StringAttribute::class,
            'type' => StringAttribute::class,
            'state' => StringAttribute::class,
            'type_id' => IntegerAttribute::class,
            'state_id' => IntegerAttribute::class,
        ];
    }

    public function relations()
    {
        return [];
    }
}
