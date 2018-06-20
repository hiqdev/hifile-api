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

use hiqdev\yii\DataMapper\models\AbstractModel;
use hiqdev\yii\DataMapper\query\attributes\DateTimeAttribute;
use hiqdev\yii\DataMapper\query\attributes\IntegerAttribute;
use hiqdev\yii\DataMapper\query\attributes\StringAttribute;
use hiqdev\yii\DataMapper\query\attributes\UuidAttribute;

/**
 * Class FileModel.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class FileModel extends AbstractModel
{
    public function attributes()
    {
        return [
            'id'            => UuidAttribute::class,
            'client_id'     => IntegerAttribute::class,
            'provider'      => StringAttribute::class,
            'provider_id'   => IntegerAttribute::class,
            'remoteid'      => StringAttribute::class,
            'mimetype'      => StringAttribute::class,
            'md5'           => StringAttribute::class,
            'mimetype_id'   => IntegerAttribute::class,
            'filename'      => StringAttribute::class,
            'size'          => IntegerAttribute::class,
            'data'          => StringAttribute::class,
            'type'          => StringAttribute::class,
            'state'         => StringAttribute::class,
            'type_id'       => IntegerAttribute::class,
            'state_id'      => IntegerAttribute::class,
            'create_time'   => DateTimeAttribute::class,
            'update_time'   => DateTimeAttribute::class,
        ];
    }

    public function relations()
    {
        return [];
    }
}
