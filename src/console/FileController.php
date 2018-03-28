<?php

namespace transmedia\signage\file\api\console;

use transmedia\signage\file\api\domain\file\FileServiceInterface;
use yii\base\Module;
use Yii;

/**
 * Class FileController
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class FileController extends \yii\console\Controller
{
    protected $fileService;

    public function __construct(
        string $id,
        Module $module,
        FileServiceInterface $fileService,
        array $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->fileService = $fileService;
    }

    public function actionFetch($id)
    {
        $file = $this->fileService->findOneOrFail($id);
        $url = $this->fileService->getRemoteUrl($file);
        $dst = $this->fileService->getDestination($file);

        $this->fetch($url, $dst);
    }

    protected function fetch($url, $dst)
    {
        $dir = dirname($dst);
        if (!file_exists($dir)) {
            exec("mkdir -p $dir");
        }
        $lock = fopen("$dir/.fetching", "c");

        if (!$lock || !flock($lock, LOCK_EX | LOCK_NB)) {
            throw new \Exception('already working');
        }

        $this->exec('/usr/bin/curl', ['-o', $dst, $url]);
    }

    protected function exec($prog, array $args)
    {
        $command = $prog;
        foreach ($args as $arg) {
            $command .= ' ' . escapeshellarg($arg);
        }

        var_dump($command);
        exec($command);
    }
}
