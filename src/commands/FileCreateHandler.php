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
     * @var User
     */
    private $user;

    /**
     * FileCreateHandler constructor.
     *
     * @param FileServiceInterface $fileService
     */
    public function __construct(FileServiceInterface $fileService, User $user)
    {
        $this->fileService = $fileService;
        $this->user = $user;
    }

    public function handle(FileCreateCommand $command)
    {
        $dto = new FileCreationDto();
        $dto->client_id = $this->user->getId();
        $dto->remoteid = $command->remoteid;
        $dto->label = $command->label;
        $dto->descr = $command->descr;

        return $this->fileService->create($dto);
    }
}
