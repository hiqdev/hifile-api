<?php

namespace transmedia\signage\file\api\providers;

use yii\di\Container;

/**
 * class ProviderFactory
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class ProviderFactory implements ProviderFactoryInterface
{
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function get(string $id): ProviderInterface
    {
        if ('filestack' === strtolower($id)) {
            return $this->container->get(FilestackProvider::class);
        }

        throw new \Exception("unknown provider: $id");
    }
}
