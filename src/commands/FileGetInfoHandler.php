<?php
/**
 * HiFile file server API
 *
 * @link      https://github.com/hiqdev/hifile-api
 * @package   hifile-api
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\hifile\api\commands;

use hiapi\commands\EntityCommandInterface;
use hiapi\commands\GetInfoHandler;
use hiqdev\hifile\api\domain\file\File;
use hiqdev\hifile\api\domain\file\FileServiceInterface;
use hiqdev\yii\DataMapper\components\EntityManagerInterface;

/**
 * Class FileGetInfoCommand.
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
        /** @var File $file */
        $file = parent::handle($command);

        return $this->fileService->ensureMetadata($file);
    }
}
