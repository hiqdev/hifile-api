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
            \hiapi\bus\ApiCommandsBusInterface::class => [
                'branches' => [
                    'file' => [
                        'search'    => \transmedia\signage\file\api\commands\FileSearchCommand::class,
                        'get-info'  => \transmedia\signage\file\api\commands\FileGetInfoCommand::class,
                        'create'    => \transmedia\signage\file\api\commands\FileCreateCommand::class,
                    ],
                ],
            ],

            'bus.per-command-middleware' => [
                'commandMiddlewares' => [
                    \transmedia\signage\file\api\commands\FileSearchCommand::class => [
                        [\hiapi\middlewares\AuthMiddleware::class, 'file.read'],
                        \hiapi\middlewares\PassthroughCommandHandler::class,
                    ],
                    \transmedia\signage\file\api\commands\FileGetInfoCommand::class => [
                        [\hiapi\middlewares\AuthMiddleware::class, 'file.read'],
                        \hiapi\middlewares\PassthroughCommandHandler::class,
                    ],
                    \transmedia\signage\file\api\commands\FileCreateCommand::class => [
                        [\hiapi\middlewares\AuthMiddleware::class, 'file.create'],
                        \hiapi\middlewares\PassthroughCommandHandler::class
                    ],
                ],
            ],

            \hiqdev\yii\DataMapper\components\EntityManagerInterface::class => [
                'repositories' => [
                    \transmedia\signage\file\api\domain\file\File::class => \transmedia\signage\file\api\persistence\FileRepository::class,
                ],
            ],
            \hiqdev\yii\DataMapper\hydrator\ConfigurableAggregateHydrator::class => [
                'hydrators' => [
                    \transmedia\signage\file\api\domain\file\File::class => \transmedia\signage\file\api\services\FileHydrator::class,
                 ],
            ],
            \transmedia\signage\file\api\domain\file\FileFactoryInterface::class => \transmedia\signage\file\api\services\FileFactory::class,
            \transmedia\signage\file\api\domain\file\FileServiceInterface::class => \transmedia\signage\file\api\services\FileService::class,
            \transmedia\signage\file\api\domain\file\FileRepositoryInterface::class => \transmedia\signage\file\api\persistence\FileRepository::class,
        /// providers
            \transmedia\signage\file\api\providers\ProviderFactoryInterface::class => \transmedia\signage\file\api\providers\ProviderFactory::class,
            \Filestack\FilestackClient::class => [
                '__construct()' => [
                    0 => $params['filestack.apiKey'],
                ],
            ],
        /// processors
            \transmedia\signage\file\api\processors\ProcessorFactoryInterface::class => [
                '__class' => \transmedia\signage\file\api\processors\ProcessorFactory::class,
                'processors' => [
                    'image' => \transmedia\signage\file\api\processors\BuiltinImageProcessor::class,
                    'video' => \transmedia\signage\file\api\processors\FfmepgProcessor::class,
                ],
            ],
        ],
    ],
];
