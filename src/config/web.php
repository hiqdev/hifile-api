<?php
/**
 * API for Transmedia
 *
 * @link      https://github.com/transmedia/hiapi
 * @package   hiapi
 * @license   proprietary
 * @copyright Copyright (c) 2018, TransMedia (http://transmedia.com.ua/)
 */

return [
    'components' => [
        'urlManager' => [
            'rules' => [
                'file-proxy' => [
                    'pattern' => 'file/<prefix:\\w+>/<id:[\\w-]+>/\\S+',
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
