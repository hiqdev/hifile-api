<?php

namespace transmedia\signage\file\api\providers;

use transmedia\signage\file\api\domain\file\FileCreationDto;

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
