<?php
/**
 * HiFile file server API
 *
 * @link      https://github.com/hiqdev/hifile-api
 * @package   hifile-api
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\hifile\api\persistence;

use hiqdev\yii\DataMapper\query\Query;

/**
 * Class FileQuery.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
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
            'provider'      => 'fp.name',
            'mimetype'      => 'fm.name',
            'filename'      => 'zf.filename',
            'size'          => 'zf.size',
            'data'          => 'zf.data',
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
            ->innerJoin('ref        fp', 'fp.obj_id = zf.provider_id')
            ->innerJoin('ref        ft', 'ft.obj_id = zf.type_id')
            ->innerJoin('ref        fs', 'fs.obj_id = zf.state_id')
            ->leftJoin('ref         fm', 'fm.obj_id = zf.mimetype_id');
    }
}
