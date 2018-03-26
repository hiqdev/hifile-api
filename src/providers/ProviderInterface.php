<?php

namespace transmedia\signage\file\api\providers;

/**
 * ProviderInterface
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
interface ProviderInterface
{
    public function getMetaData($id): array;
}
