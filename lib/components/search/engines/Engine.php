<?php
namespace app\lib\components\search\engines;

/**
 * Class Engine
 * @package app\lib\components\search\engines
 *
 * Abstract interface for Engine class
 * Design Pattern: abstract factory
 */
abstract class Engine
{
    /**
     * Engine constructor.
     * @param array $config
     * Config format: ['host' => 'localhost', 'port' => '9312', 'indexName' => 'myindex1']
     */
    public function __construct(array $config = [])
    {
        //update selected properties with values from input data
        foreach ($config as $property => $value) {
            if (property_exists($this, $property)) {
                $this->$property = $value;
            }
        }
    }

    /**
     * Method for search in selected Engine storage by input query request
     * @param mixed $query
     * @param array $params
     * @return mixed
     * Query format: 'TV-tuner'
     */
    abstract public function search($query, $params = []);
}