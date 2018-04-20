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
    'controllerMap' => [
        'file' => [
            'class' => \hiqdev\hifile\api\console\FileController::class,
        ],
    ],
    'container' => [
        'singletons' => [
            'console.default-user' => [
                'roles' => [
                    'files' => 'role:file.manager',
                ],
            ],
        /// events consuming
            \hiqdev\yii2\autobus\components\AutoBusFactoryInterface::class => [
                'mapping' => [
                    'file.events' => 'queue-bus.router-for.file.events',
                ],
            ],
            'queue-bus.router-for.file.events' => [
                '__class' => \hiqdev\yii2\autobus\components\BranchedAutoBus::class,
                '__construct()' => [
                    0 => \yii\di\Instance::of('bus.the-bus'),
                ],
                'branches' => [
                    'file' => [
                        'was-created' => \hiqdev\hifile\api\commands\FileProbeCommand::class,
                        'got-ready' => \hiqdev\hifile\api\commands\FileNotifyCommand::class,
                    ],
                ],
            ],
        ],
    ],
];
