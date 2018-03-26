<?php

namespace transmedia\signage\file\api\providers;

/**
 * ProviderFactoryInterface
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
interface ProviderFactoryInterface
{
    public function get(string $id): ProviderInterface;
}
