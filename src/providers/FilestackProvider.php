<?php

namespace transmedia\signage\file\api\providers;

use Filestack\FilestackClient;

/**
 * class FilestackProvider
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class FilestackProvider implements ProviderInterface
{
    public function __construct(FilestackClient $filestack)
    {
        $this->filestack = $filestack;
    }

    public function getMetaData($handle): array
    {
        return $this->filestack->getMetaData($handle, ['mimetype', 'size', 'filename']);
    }
}
