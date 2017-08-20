<?php
namespace app\components\import\importers;

use yii\base\Model;

/**
 * Class Importer
 * @package app\components\import\importers
 */
abstract class Importer extends \yii\base\Component
{
    /**
     * @param array $params
     * @return mixed
     */
    abstract public function import($params = []);
}