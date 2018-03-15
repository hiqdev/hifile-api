<?php

namespace transmedia\signage\file\api\commands;

use transmedia\signage\file\api\domain\file\FileServiceInterface;
use transmedia\signage\file\api\domain\file\FileCreationDto;
use yii\web\User;

/**
 * Class FileCreateHandler
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class FileCreateHandler
{
    /**
     * @var FileServiceInterface
     */
    private $fileService;

    /**
     * FileCreateHandler constructor.
     *
     * @param FileServiceInterface $fileService
     */
    public function __construct(FileServiceInterface $fileService)
    {
        $this->fileService = $fileService;
    }

    public function handle(FileCreateCommand $command)
    {
        $dto = new FileCreationDto();
        $dto->remoteid = $command->remoteid;
        $dto->label = $command->label;
        $dto->descr = $command->descr;

        return $this->fileService->create($dto);
    }
}
