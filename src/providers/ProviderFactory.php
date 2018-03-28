<?php

namespace transmedia\signage\file\api\providers;

use transmedia\signage\file\api\domain\file\FileCreationDto;
use yii\di\Container;

/**
 * class ProviderFactory
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class ProviderFactory implements ProviderFactoryInterface
{
    protected $providers = [
        'filestack' => FilestackProvider::class,
    ];

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function detect(FileCreationDto $dto): void
    {
        foreach ($this->providers as $id => $class) {
            if ($class::detect($dto)) {
                $dto->provider = $id;
                return;
            }
        }

        throw new \Exception('cannot detect file provider');

    }

    public function get(string $id): ProviderInterface
    {
        if (empty($this->providers[$id])) {
            throw new \Exception("unknown provider: $id");
        }

        return $this->container->get($this->providers[$id]);
    }
}
