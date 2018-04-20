<?php

namespace hiqdev\hifile\api\services;

use hiqdev\hifile\api\domain\file\File;

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
