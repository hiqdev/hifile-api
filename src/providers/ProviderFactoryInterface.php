<?php

namespace hiqdev\hifile\api\providers;

use hiqdev\hifile\api\domain\file\FileCreationDto;

/**
 * ProviderFactoryInterface
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
interface ProviderFactoryInterface
{
    public function detect(FileCreationDto $dto): void;

    public function get(string $id): ProviderInterface;
}
