<?php
namespace app\lib\components\search;

/**
 * Class Search
 * @package app\lib\components\search
 *
 * Class for search data in storage
 * Design Pattern: factory method
 *
 * Example of using:
 *   $result = $search
 *                ->getEngine(\app\lib\components\search\Search::ENGINE_SPHINX, [ ... ])
 *                ->search('TV-tuner');
 */
class Search
{
    //available type of engine for search
    const ENGINE_SPHINX = 'sphinx';

    /**
     * Available engines for search
     * @var array
     */
    protected $engines = [
        self::ENGINE_SPHINX => '\app\lib\components\search\engines\SphinxEngine',
    ];

    /**
     * Get instance of selected engine
     * @param string $engine
     * @param array  $config Engine configuration
     * @return \app\lib\components\search\engines\Engine   Instance object
     */
    public function getEngine($engine, array $config = [])
    {
        //get Class for selected engine
        $engineClass = $this->engines[$engine];
        return new $engineClass($config);
    }
}