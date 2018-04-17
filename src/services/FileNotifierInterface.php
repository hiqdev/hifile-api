<?php

namespace transmedia\signage\file\api\services;

use transmedia\signage\file\api\domain\file\File;

/**
 * Interface FileNotifierInterface
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
interface FileNotifierInterface
{
    /**
     * @param File $file
     */
    public function notify(File $file): void;
}
