<?php

namespace transmedia\signage\file\api\commands;

use hiapi\commands\GetInfoHandler;
use hiapi\commands\EntityCommandInterface;
use hiqdev\yii\DataMapper\components\EntityManagerInterface;
use transmedia\signage\file\api\domain\file\FileServiceInterface;

/**
 * Class FileGetInfoCommand
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class FileGetInfoHandler extends GetInfoHandler
{
    /**
     * @var FileServiceInterface
     */
    private $fileService;

    public function __construct(EntityManagerInterface $em, FileServiceInterface $fileService)
    {
        parent::__construct($em);
        $this->fileService = $fileService;
    }

    public function handle(EntityCommandInterface $command)
    {
        $file = parent::handle($command);

        return $this->fileService->ensureMetadata($file);
    }
}
