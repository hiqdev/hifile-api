<?php

namespace hiqdev\hifile\api\console;

use hiqdev\hifile\api\domain\file\File;
use hiqdev\hifile\api\domain\file\FileServiceInterface;
use hiqdev\hifile\api\processors\ProcessorInterface;
use hiqdev\hifile\api\processors\ProcessorFactoryInterface;
use yii\base\Module;
use Yii;

/**
 * Class FileController
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class FileController extends \yii\console\Controller
{
    /**
     * @var FileServiceInterface
     */
    protected $fileService;

    /**
     * @var ProcessorFactoryInterface
     */
    private $processorFactory;

    public function __construct(
        string $id,
        Module $module,
        FileServiceInterface $fileService,
        ProcessorFactoryInterface $processorFactory,
        array $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->fileService = $fileService;
        $this->processorFactory = $processorFactory;
    }

    public function actionFetch($id)
    {
        $file = $this->fileService->findOneOrFail($id);

        $this->fetch($file);
    }

    public function actionProbe($id)
    {
        $file = $this->fileService->findOneOrFail($id);
        $dst = $this->fileService->getDestination($file);
        if (!file_exists($dst)) {
            $this->fetch($file);
        }

        $proc = $this->processorFactory->get($file);
        $info = $proc->collectInfo($dst);
        $this->fileService->setMetaData($file, $info);
    }

    protected function fetch(File $file)
    {
        $url = $this->fileService->getRemoteUrl($file);
        $dst = $this->fileService->getDestination($file);
        $dir = dirname($dst);
        if (!file_exists($dir)) {
            exec("mkdir -p $dir");
        }
        $lock = fopen("$dir/.fetching", "c");

        if (!$lock || !flock($lock, LOCK_EX | LOCK_NB)) {
            throw new \Exception('already working');
        }

        $this->exec('/usr/bin/curl', ['-o', $dst, $url]);
        if (!file_exists($dst)) {
            throw new \Exception("failed fetch file: $id");
        }
    }

    protected function exec($prog, array $args): array
    {
		$skips = [
            '> /dev/null' => 1,
            '2>&1' => 1,
		];
        $command = $prog;
        foreach ($args as $arg) {
			$arg = isset($skips[$arg]) ? $arg : escapeshellarg($arg);
            $command .= ' ' . $arg;
        }

        var_dump($command);
        exec($command, $output);

		return $output;
    }
}
