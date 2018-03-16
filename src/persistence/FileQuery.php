<?php

namespace transmedia\signage\file\api\persistence;

use hiqdev\yii\DataMapper\query\Query;

class FileQuery extends Query
{
    /**
     * @var string
     */
    protected $modelClass = FileModel::class;

    protected function attributesMap()
    {
        return [
            'id'            => 'zf.id',
            'client_id'     => 'zf.client_id',
            'remoteid'      => 'zf.remoteid',
            'type'          => 'ft.name',
            'type_id'       => 'zf.type_id',
            'state'         => 'fs.name',
            'state_id'      => 'zf.state_id',
            'create_time'   => 'zf.create_time',
            'update_time'   => 'zf.update_time',
        ];
    }

    public function initFrom()
    {
        return $this->from('file    zf')
            ->innerJoin('ref        ft', 'ft.obj_id = zf.type_id')
            ->innerJoin('ref        fs', 'fs.obj_id = zf.state_id')
        ;
    }
}
