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
    'container' => [
        'singletons' => [
        /// User
            \yii\web\User::class => function () {
                return new \yii\web\User([
                    'identityClass' => \transmedia\hiapi\models\UserIdentity::class,
                ]);
            },
        // BUS
            \hiapi\bus\ApiCommandsBusInterface::class => [
                'branches' => [
                    'file' => [
                        'search' => \transmedia\signage\file\api\commands\FileSearchCommand::class,
                        'create' => \transmedia\signage\file\api\commands\FileCreateCommand::class,
                    ],
                ],
            ],

            'bus.per-command-middleware' => [
                'commandMiddlewares' => [
                    \transmedia\signage\file\api\commands\FileSearchCommand::class => [
                        [\hiapi\middlewares\AuthMiddleware::class, 'file.read'],
                        \hiapi\middlewares\PassthroughCommandHandler::class,
                    ],
                    \transmedia\signage\file\api\commands\FileCreateCommand::class => [
                        [\hiapi\middlewares\AuthMiddleware::class, 'file.create'],
                        \hiapi\middlewares\PassthroughCommandHandler::class
                    ],
                ],
            ],
        ],
    ],
];
