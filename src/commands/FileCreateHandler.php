<?php

namespace transmedia\hiapi\modules\file\commands;

use transmedia\hiapi\modules\file\domain\FileServiceInterface;
use transmedia\hiapi\modules\file\domain\dto\FileCreationDto;
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
        $dto->label = $command->label;
        $dto->descr = $command->descr;

        return $this->fileService->create($dto);
    }
}
