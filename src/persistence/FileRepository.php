<?php

namespace transmedia\signage\file\api\persistence;

use hiqdev\yii\DataMapper\components\ConnectionInterface;
use hiqdev\yii\DataMapper\components\EntityManagerInterface;
use hiqdev\yii\DataMapper\expressions\CallExpression;
use hiqdev\yii\DataMapper\expressions\HstoreExpression;
use hiqdev\yii\DataMapper\query\Specification;
use hiqdev\yii\DataMapper\repositories\BaseRepository;
use Ramsey\Uuid\Uuid;
use transmedia\signage\file\api\domain\file\File;
use transmedia\signage\file\api\domain\file\FileFactoryInterface;
use transmedia\signage\file\api\domain\file\FileRepositoryInterface;
use yii\db\Query;
use yii\filters\auth\QueryParamAuth;

/**
 * Class FileRepository
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class FileRepository extends BaseRepository implements FileRepositoryInterface
{
    /** {@inheritdoc} */
    public $queryClass = FileQuery::class;

    /**
     * @var FileFactoryInterface
     */
    protected $factory;

    public function __construct(
        ConnectionInterface $db,
        EntityManagerInterface $em,
        FileFactoryInterface $factory,
        array $config = []
    ) {
        parent::__construct($db, $em, $config);

        $this->factory = $factory;
    }

    /** {@inheritdoc} */
    public function create(File $file): File
    {
        $query = (new Query())->select([
            new CallExpression('add_file', new HstoreExpression([
                'client_id' => $file->getClientId(),
                'remoteid'  => $file->getRemoteId(),
                'label'     => $file->getLabel(),
                'descr'     => $file->getDescr(),
                'type'      => $file->getType(),
                'state'     => $file->getState(),
            ]))
        ]);
        $id = Uuid::fromString($query->scalar());

        if ($id === null) {
            throw new \RuntimeException('Failed to create file');
        }

        $this->factory->hydrate(['id' => $id], $file);

        return $file;
    }

    /**
     * @param File $file
     */
    public function delete(File $file): void
    {
        $this->db->createCommand()->delete('file', ['obj_id' => (int)$file->getId()])->execute();
    }

    /**
     * @param File $file
     */
    public function persist(File $file): void
    {
        $query = (new Query())->select([
            new CallExpression('set_file', new HstoreExpression([
                'id' => $file->getId(),
                'type' => $file->getType(),
                'state' => $file->getState(),
            ]))
        ]);
        $id = $query->scalar();

        if ($id === null) {
            throw new \RuntimeException('Failed to persist file');
        }
    }
}