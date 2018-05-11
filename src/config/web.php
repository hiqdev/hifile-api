<?php
/**
 * HiFile file server API
 *
 * @link      https://github.com/hiqdev/hifile-api
 * @package   hifile-api
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2018, HiQDev (http://hiqdev.com/)
 */

return [
    'components' => [
        'urlManager' => [
            'rules' => [
                'file-proxy' => [
                    'pattern' => 'file/<prefix:\w+>/<id:[\w-]+>/<dumb:.*>',
                    'route' => 'file/proxy/proxify',
                ],
            ],
        ],
    ],
    'modules' => [
        'file' => [
            'class' => \hiqdev\hifile\api\Module::class,
        ],
    ],
];
