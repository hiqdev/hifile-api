<?php
/**
 * API for Transmedia
 *
 * @link      https://github.com/transmedia/hiapi
 * @package   hiapi
 * @license   proprietary
 * @copyright Copyright (c) 2018, TransMedia (http://transmedia.com.ua/)
 */

/**
 * @var array $params
 */

use yii\di\Instance;

return [
    'container' => [
        'singletons' => [
            \hiqdev\yii\DataMapper\hydrator\ConfigurableAggregateHydrator::class => [
                'hydrators' => [
                     //\transmedia\signage\api\models\File::class => \transmedia\signage\api\models\FileHydrator::class,
                 ],
            ],
            // Command bus:
            \League\Tactician\Handler\CommandNameExtractor\CommandNameExtractor::class => \League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor::class,
            \League\Tactician\Handler\Locator\HandlerLocator::class => \hiqdev\yii2\autobus\bus\NearbyHandlerLocator::class,
            \League\Tactician\Handler\MethodNameInflector\MethodNameInflector::class => \League\Tactician\Handler\MethodNameInflector\HandleInflector::class,

            \hiqdev\yii2\autobus\components\CommandFactoryInterface::class => \hiqdev\yii2\autobus\components\SimpleCommandFactory::class,

            \hiapi\bus\ApiCommandsBusInterface::class => [
                'branches' => [
                    'file' => [
                        'search' => \transmedia\signage\file\api\commands\FileSearchCommand::class,
                        'create' => \transmedia\signage\file\api\commands\FileCreateCommand::class,
                    ],
                ],
            ],

            'bus.http-request' => [
                '__class' => \hiqdev\yii2\autobus\components\TacticianCommandBus::class,
                '__construct()' => [
                    Instance::of('bus.http-request.default-command-handler'),
                ],
                'middlewares' => [
                    $_ENV['ENABLE_JSONAPI_RESPONSE'] ?? false
                        ? \hiapi\middlewares\JsonApiMiddleware::class
                        : \hiapi\middlewares\LegacyResponderMiddleware::class,
                    \hiapi\middlewares\HandleExceptionsMiddleware::class,
                    \hiqdev\yii2\autobus\bus\LoadFromRequestMiddleware::class,
                    \hiqdev\yii2\autobus\bus\ValidateMiddleware::class,
                    'bus.http-request.per-command-middleware',
                ],
            ],
            'bus.http-request.per-command-middleware' => [
                '__class' => \hiapi\middlewares\PerCommandMiddleware::class,
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
            'bus.http-request.default-command-handler' => [
                '__class' => \League\Tactician\Handler\CommandHandlerMiddleware::class,
                '__construct()' => [
                    Instance::of(\League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor::class),
                    Instance::of(\hiqdev\yii2\autobus\bus\NearbyHandlerLocator::class),
                    Instance::of(\League\Tactician\Handler\MethodNameInflector\HandleInflector::class),
                ],
            ],

            // Data-access layer:
            \Zend\Hydrator\HydratorInterface::class => function ($container) {
                return $container->get(\hiqdev\yii\DataMapper\hydrator\ConfigurableAggregateHydrator::class);
            },

            // Domain layer related:
            \hiqdev\yii\DataMapper\components\EntityManagerInterface::class => [
                '__class' => \hiqdev\yii\DataMapper\components\EntityManager::class,
                'repositories' => [
                ],
            ],

            // General
            \yii\di\Container::class => function ($container) {
                return $container;
            },
            \Psr\Http\Message\ServerRequestInterface::class => function ($container) {
                return \GuzzleHttp\Psr7\ServerRequest::fromGlobals();
            },
            \Psr\Http\Message\ResponseInterface::class => function ($container) {
                return new \GuzzleHttp\Psr7\Response();
            },
            \WoohooLabs\Yin\JsonApi\Request\RequestInterface::class => \WoohooLabs\Yin\JsonApi\Request\Request::class,
            \WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface::class => \WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory::class,

            \yii\web\User::class => function () {
                return new \yii\web\User([
                    'identityClass' => \transmedia\hiapi\models\UserIdentity::class,
                ]);
            },
        ]
    ]
];
