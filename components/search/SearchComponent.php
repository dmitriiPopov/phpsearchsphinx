<?php
namespace app\components\search;

use yii\base\Component;
use app\components\search\finders\Finder;
use yii\base\Exception;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class Search
 * @package app\lib\components\search
 *
 * Class for search data in storage
 * Design Pattern: factory method
 *
 * Example of using:
 *   $result = Yii::$app->search->getFinder('items')->search('TV-tuner');
 */
class SearchComponent extends Component
{
    /**
     * @var array
     */
    public $finders = [];

    /**
     * @var array
     */
    protected $findersInstances = [];

    /**
     * Get instance of selected finder
     * @param string $finderName
     * @param array  $config Finder configuration
     * @param boolean $force
     * @return Finder
     * @throws Exception
     */
    public function getFinder($finderName, array $config = [], $force = false)
    {
        if (!isset($this->finders[$finderName])) {
            throw new Exception('Invalid name of Finder. Check it in config!');
        }

        if ($force || !isset($this->findersInstances[$finderName])) {
            $config = ArrayHelper::merge($this->finders[$finderName], $config);
            $this->findersInstances[$finderName] = Yii::createObject($config);
        }

        return $this->findersInstances[$finderName];
    }
}