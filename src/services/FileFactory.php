<?php
/**
 * HiFile file server API
 *
 * @link      https://github.com/hiqdev/hifile-api
 * @package   hifile-api
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\hifile\api\services;

use hiqdev\hifile\api\domain\file\File;
use hiqdev\hifile\api\domain\file\FileCreationDto;
use hiqdev\hifile\api\domain\file\FileFactoryInterface;
use hiqdev\yii\DataMapper\factories\HydratorAwareFactoryTrait;
use Zend\Hydrator\HydratorInterface;

/**
 * Class FileFactory.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class FileFactory implements FileFactoryInterface
{
    use HydratorAwareFactoryTrait;

    /**
     * @var HydratorInterface
     */
    private $hydrator;

    public function __construct(HydratorInterface $hydrator)
    {
        $this->hydrator = $hydrator;
    }

    public function getEntityClassName(): string
    {
        return File::class;
    }

    /**
     * @param FileCreationDto $dto
     * @return File
     */
    public function create(FileCreationDto $dto): File
    {
        $file = new File($dto->client_id, $dto->provider, $dto->remoteid);
        if ($dto->label !== null) {
            $file->setLabel($dto->label);
        }
        if ($dto->descr !== null) {
            $file->setDescr($dto->descr);
        }
        if ($dto->type !== null) {
            $file->setType($dto->type);
        }

        return $file;
    }
}
