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
use hiqdev\hifile\api\services\FileService;
use Yii;
use yii\base\Module;

/**
 * Class ApiController.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class ProxyController extends \yii\web\Controller
{
    /**
     * @var FileServiceInterface|FileService
     */
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

    public function actionProxify($prefix, $id, $filename)
    {
        $file = $this->fileService->findOneOrFail($id);

        $current = Yii::$app->request->getUrl();
        $canonic = '/file/' . $this->fileService->getFilePath($file, $filename);
        if (urldecode($current) !== $canonic) {
            return $this->redirect($canonic);
        }
        $url = $this->fileService->getRemoteUrl($file);

        return $this->redirect($url);
    }
}
