<?php
/**
 * HiFile file server API
 *
 * @link      https://github.com/hiqdev/hifile-api
 * @package   hifile-api
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\hifile\api\controllers;

use hiqdev\hifile\api\domain\file\FileServiceInterface;
use Yii;
use yii\base\Module;

/**
 * Class ApiController.
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
