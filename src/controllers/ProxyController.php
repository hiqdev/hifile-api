<?php

namespace transmedia\signage\file\api\controllers;

use transmedia\signage\file\api\domain\file\FileServiceInterface;
use yii\base\Module;
use Yii;

/**
 * Class ApiController
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class ProxyController extends \yii\web\Controller
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

    public function actionProxify($prefix, $id)
    {
        $file = $this->fileService->findOneOrFail($id);

        $current = Yii::$app->request->getUrl();
        $canonic = $this->fileService->getUrl($file);
        if ($current !== $canonic) {
            return $this->redirect($canonic);
        }

        $this->fileService->fetchFile($file);
        $url = $this->fileService->getRemoteUrl($file);

        return $this->redirect($url);
    }
}
