<?php

namespace transmedia\signage\file\api\providers;

use transmedia\signage\file\api\domain\file\File;
use transmedia\signage\file\api\domain\file\FileCreationDto;

/**
 * ProviderInterface
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
interface ProviderInterface
{
    public function getMetaData($id): array;

    public function getRemoteUrl(File $file): string;

    public static function detect(FileCreationDto $dto): bool;
}
