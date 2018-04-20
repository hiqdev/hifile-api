<?php

namespace hiqdev\hifile\api\commands;

use hiqdev\hifile\api\domain\file\FileServiceInterface;

/**
 * Class FileNotifyHandler
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class FileNotifyHandler
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

    public function handle(FileNotifyCommand $command)
    {

        $file = $this->fileService->findOneOrFail($command->id);
        $this->fileService->notify($file);

        return $file;
    }
}
