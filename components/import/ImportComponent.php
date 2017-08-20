<?php
namespace app\components\import;

use app\models\Shop;
use yii\base\Exception;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class ImportComponent
 * @package app\components\import
 */
class ImportComponent extends \yii\base\Component
{
    const IMPORTER_SHOP_CSV = 'shopCsv';

    /**
     * @var array
     */
    public $importers = [];

    /**
     * @var array
     */
    protected $importersInstances = [];

    /**
     * Get instance of selected importer
     * @param string $finderName
     * @param array  $config Finder configuration
     * @param boolean $force
     * @return \yii\base\Component
     * @throws Exception
     */
    public function getImporter($finderName, array $config = [], $force = false)
    {
        if (!isset($this->importers[$finderName])) {
            throw new Exception('Invalid name of Finder. Check it in config!');
        }

        if ($force || !isset($this->importersInstances[$finderName])) {
            $config = ArrayHelper::merge($this->importers[$finderName], $config);
            $this->importersInstances[$finderName] = Yii::createObject($config);
        }

        return $this->importersInstances[$finderName];
    }


}