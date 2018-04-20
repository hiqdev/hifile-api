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
    'container' => [
        'singletons' => [
            \hiapi\bus\ApiCommandsBusInterface::class => [
                'branches' => [
                    'file' => [
                        'search'    => \hiqdev\hifile\api\commands\FileSearchCommand::class,
                        'get-info'  => \hiqdev\hifile\api\commands\FileGetInfoCommand::class,
                        'create'    => \hiqdev\hifile\api\commands\FileCreateCommand::class,
                        'fetch'     => \hiqdev\hifile\api\commands\FileFetchCommand::class,
                        'probe'     => \hiqdev\hifile\api\commands\FileProbeCommand::class,
                        'notify'    => \hiqdev\hifile\api\commands\FileNotifyCommand::class,
                    ],
                ],
            ],

            'bus.per-command-middleware' => [
                'commandMiddlewares' => [
                    \hiqdev\hifile\api\commands\FileSearchCommand::class => [
                        [\hiapi\middlewares\AuthMiddleware::class, 'file.read'],
                        \hiapi\middlewares\PassthroughCommandHandler::class,
                    ],
                    \hiqdev\hifile\api\commands\FileGetInfoCommand::class => [
                        [\hiapi\middlewares\AuthMiddleware::class, 'file.read'],
                        \hiapi\middlewares\PassthroughCommandHandler::class,
                    ],
                    \hiqdev\hifile\api\commands\FileCreateCommand::class => [
                        [\hiapi\middlewares\AuthMiddleware::class, 'file.create'],
                        \hiapi\middlewares\PassthroughCommandHandler::class,
                    ],
                    \hiqdev\hifile\api\commands\FileFetchCommand::class => [
                        [\hiapi\middlewares\AuthMiddleware::class, 'file.fetch'],
                        \hiapi\middlewares\PassthroughCommandHandler::class,
                    ],
                    \hiqdev\hifile\api\commands\FileProbeCommand::class => [
                        [\hiapi\middlewares\AuthMiddleware::class, 'file.probe'],
                        \hiapi\middlewares\PassthroughCommandHandler::class,
                    ],
                    \hiqdev\hifile\api\commands\FileNotifyCommand::class => [
                        [\hiapi\middlewares\AuthMiddleware::class, 'file.notify'],
                        \hiapi\middlewares\PassthroughCommandHandler::class,
                    ],
                ],
            ],

            \hiqdev\yii\DataMapper\components\EntityManagerInterface::class => [
                'repositories' => [
                    \hiqdev\hifile\api\domain\file\File::class => \hiqdev\hifile\api\persistence\FileRepository::class,
                ],
            ],
            \hiqdev\yii\DataMapper\hydrator\ConfigurableAggregateHydrator::class => [
                'hydrators' => [
                    \hiqdev\hifile\api\domain\file\File::class => \hiqdev\hifile\api\services\FileHydrator::class,
                 ],
            ],
            \hiqdev\hifile\api\domain\file\FileFactoryInterface::class => \hiqdev\hifile\api\services\FileFactory::class,
            \hiqdev\hifile\api\domain\file\FileServiceInterface::class => \hiqdev\hifile\api\services\FileService::class,
            \hiqdev\hifile\api\domain\file\FileRepositoryInterface::class => \hiqdev\hifile\api\persistence\FileRepository::class,
        /// notifier
            \hiqdev\hifile\api\services\FileNotifierInterface::class => [
                '__class' => \hiqdev\hifile\api\services\NotifyToQueue::class,
                'queue' => 'core.from-file',
            ],
        /// providers
            \hiqdev\hifile\api\providers\ProviderFactoryInterface::class => \hiqdev\hifile\api\providers\ProviderFactory::class,
            \Filestack\FilestackClient::class => [
                '__construct()' => [
                    0 => $params['filestack.apiKey'],
                ],
            ],
        /// processors
            \hiqdev\hifile\api\processors\ProcessorFactoryInterface::class => [
                '__class' => \hiqdev\hifile\api\processors\ProcessorFactory::class,
                'processors' => [
                    'image' => \hiqdev\hifile\api\processors\BuiltinImageProcessor::class,
                    'video' => \hiqdev\hifile\api\processors\FfmpegProcessor::class,
                ],
            ],
            \hiqdev\hifile\api\processors\ThumbMakerInterface::class => [
                '__class'   => \hiqdev\hifile\api\processors\SimpleThumbMaker::class,
                'width'     => 320,
                'height'    => 240,
            ],
        /// events publishing
            'file.event-listener' => [
                '__class' => \hiapi\event\PublishToQueueListener::class,
                'queue' => 'file.events',
            ],
            \League\Event\EmitterInterface::class => [
                'listeners' => [
                    'file' => [
                        'event' => 'File*',
                        'listener' => 'file.event-listener',
                    ],
                ],
            ],
        ],
    ],
];
