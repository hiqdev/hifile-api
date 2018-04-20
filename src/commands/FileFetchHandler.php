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

use hiqdev\hifile\api\domain\file\FileServiceInterface;

/**
 * Class FileFetchHandler.
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
