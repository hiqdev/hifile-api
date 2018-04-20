<?php
/**
 * HiFile file server API
 *
 * @link      https://github.com/hiqdev/hifile-api
 * @package   hifile-api
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2018, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\hifile\api\processors;

use hiqdev\hifile\api\domain\file\File;
use yii\di\Container;

/**
 * class ProcessorFactory.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class ProcessorFactory extends \yii\base\BaseObject implements ProcessorFactoryInterface
{
    protected $container;

    public function __construct(Container $container, array $config = [])
    {
        parent::__construct($config);
        $this->container = $container;
    }

    protected $_processors = [];

    public function setProcessors(array $processors)
    {
        $this->processors = $processors;
    }

    public function get(File $file): ProcessorInterface
    {
        $mime = $file->getMimeType();
        [$type, $subtype] = explode('/', $mime, 2);

        foreach ([$mime, $subtype, $type] as $id) {
            if (isset($this->processors[$id])) {
                return $this->container->get($this->processors[$id]);
            }
        }

        throw new \Exception("couldn't find processor for '$mime'");
    }
}
