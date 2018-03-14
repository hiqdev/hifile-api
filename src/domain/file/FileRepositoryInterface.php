<?php

namespace transmedia\signage\file\api\persistence;

use hiqdev\yii\DataMapper\repositories\GenericRepositoryInterface;
use transmedia\signage\file\api\domain\file\File;

/**
 * Interface FileRepositoryInterface
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
interface FileRepositoryInterface extends GenericRepositoryInterface
{
    /**
     * @param File $file
     * @return File
     */
    public function create(File $file): File;

    /**
     * @param File $file
     */
    public function delete(File $file): void;

    /**
     * @param File $file
     */
    public function persist(File $file): void;
}
