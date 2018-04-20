<?php
/**
 * HiFile file server API
 *
 * @link      https://github.com/hiqdev/hifile-api
 * @package   hifile-api
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\hifile\api\providers;

use hiqdev\hifile\api\domain\file\FileCreationDto;
use yii\di\Container;

/**
 * class ProviderFactory.
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
