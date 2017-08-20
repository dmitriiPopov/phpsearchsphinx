<?php
namespace app\components\search\finders;

/**
 * Class SphinxEngine
 * @package app\lib\components\search\engines
 *
 * Engine for search in sphinx index
 *
 * @link http://sphinxsearch.com/docs/current.html
 */
class SphinxFinder extends Finder
{
    /**
     * @var string
     */
    public $host = 'localhost';

    /**
     * @var integer
     */
    public $port = 9312;

    /**
     * @var string
     */
    public $indexName  = 'myindex1';

    /**
     * Instance of API Client object
     * @var \SphinxClient
     */
    protected $sphinxClient;


    public function init()
    {
        parent::init();

        //include sphinx api official library (independent from php-version)
        include(dirname(__DIR__) . '/extensions/sphinxapi.php');
        //instantiate client class from sphinxapi
        $this->sphinxClient = new \SphinxClient();
        //add required server parameters
        $this->sphinxClient->setServer($this->host, $this->port);
    }

    /**
     * @param mixed $query
     * @param array $params
     * @return array
     * Query format: 'TV-tuner'
     * Result format: [ ['name' => X, 'categoryId' => Y, ...], [...], [...], ... ]
     */
    public function search($query, $params = [])
    {
        //set default result value
        $result = [];

        //add different additional parameters
        $this->sphinxClient->setMatchMode(SPH_MATCH_ANY);
        $this->sphinxClient->setMaxQueryTime(3);

        //random sort mode
        $this->sphinxClient->SetSortMode(SPH_SORT_EXTENDED, '@random');

        //format input query to appropriated view
        $formattedQuery = sprintf('@* %s', trim($query));

        //show in sphinx selected index by selected query
        $response = $this->sphinxClient->Query($formattedQuery, $this->indexName);

        //if list of values from response isn't empty...
        if (isset($response['matches']) && !empty($response['matches'])) {
            //prepare result in appropriated format
            $result = array_map(function($value) {
                return $value['attrs'];
            }, $response['matches']);
            //reset primary keys
            $result = array_values($result);
        }

        return $result;
    }
}
