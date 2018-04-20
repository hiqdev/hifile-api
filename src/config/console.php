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
