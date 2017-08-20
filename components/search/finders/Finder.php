<?php
namespace app\components\search\finders;

use yii\base\Object;


/**
 * Class Engine
 * @package app\components\search\engines
 *
 * Abstract interface for Finder class
 */
abstract class Finder extends Object
{
    /**
     * Method for search in selected Engine storage by input query request
     * @param mixed $query
     * @param array $params
     * @return mixed
     * Query format: 'TV-tuner'
     */
    abstract public function search($query, $params = []);
}