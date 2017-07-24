<?php
//include logic for autoload classes
include_once('../bootstrap.php');

//class for handling input http-request
$request = new \app\lib\Request();

//class for preparing response output data
$response = new \app\lib\Response();

//exclude not-GET requests
if ( ! $request->isGetRequest()) {
    //output fail result in JSON-format
    $response->outputJsonError('For GET-request only.');
}

//include common static configuration
$config = require('../config/config.php');

//instantiate component for search by request data
$search = new \app\lib\components\search\Search();

//search via `Search::ENGINE_SPHINX`(sphinx) with input query value
$result = $search
    ->getEngine(\app\lib\components\search\Search::ENGINE_SPHINX, $config['sphinx'])
    ->search($request->getQuery());

//output success result in JSON-format
$response->outputJsonSuccess($result);
