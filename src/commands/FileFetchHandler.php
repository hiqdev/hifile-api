<?php

namespace transmedia\signage\file\api\commands;

use transmedia\signage\file\api\domain\file\FileServiceInterface;

/**
 * Class FileFetchHandler
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class FileFetchHandler
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

    public function handle(FileFetchCommand $command)
    {

        $file = $this->fileService->findOneOrFail($command->id);
        $this->fileService->fetch($file);

        return $file;
    }
}