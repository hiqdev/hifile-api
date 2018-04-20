<?php

namespace hiqdev\hifile\api\commands;

use hiqdev\hifile\api\domain\file\FileServiceInterface;
use hiqdev\hifile\api\domain\file\FileCreationDto;
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

    /**
     * Example URL: https://cdn.filestackcontent.com/ttc9Vr9SjybPZ0W3Frys
     */
    public function handle(FileCreateCommand $command)
    {
        $dto = new FileCreationDto();
        $dto->client_id = $this->user->getId();
        $dto->url = $command->url;
        $dto->label = $command->label;
        $dto->descr = $command->descr;

        return $this->fileService->create($dto);
    }
}
