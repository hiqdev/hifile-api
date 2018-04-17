<?php

namespace transmedia\signage\file\api\commands;

use transmedia\signage\file\api\domain\file\FileServiceInterface;

/**
 * Class FileProbeHandler
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class FileProbeHandler
{
    /**
     * @var FileServiceInterface
     */
    private $fileService;

    /**
     * @param FileServiceInterface $fileService
     */
    public function __construct(FileServiceInterface $fileService)
    {
        $this->fileService = $fileService;
    }

    public function handle(FileProbeCommand $command)
    {

        $file = $this->fileService->findOneOrFail($command->id);
        $this->fileService->probe($file);

        return $file;
    }
}
