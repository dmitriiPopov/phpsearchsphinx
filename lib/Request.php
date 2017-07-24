<?php
namespace app\lib;

/**
 * Class Request
 * @package app\lib
 *
 * Class for handling input http-request
 */
class Request
{
    /**
     * Name of query GET/POST parameter
     * @var string
     * Example of using in url: example.com?q=TV-tuner
     */
    public $queryParameterName = 'q';

    /**
     * Query GET parameter's value (e.g. value of `q`)
     * @var string
     */
    protected $query;

    /**
     * Check on GET request
     * @return bool
     */
    public function isGetRequest()
    {
        //check superglobal SERVER array
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    /**
     * Get query value from GET/POST request
     * @return null | string
     */
    public function getQuery()
    {
        //create default query value
        $query = null;

        //check request data for query parameter
        if (isset($_REQUEST[$this->queryParameterName]) && !empty($_REQUEST[$this->queryParameterName]) && !is_array($query)) {
            //decode from url-encoded format
            $query = urldecode($_REQUEST[$this->queryParameterName]);
            //exclude possible sql-injection, xss-attack and other invalid format (required secure formatting)
            $query = strip_tags($query);
            $query = htmlentities($query, ENT_QUOTES, "UTF-8");
            $query = htmlspecialchars($query, ENT_QUOTES);
        }

        return $query;
    }
}