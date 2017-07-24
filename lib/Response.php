<?php
namespace app\lib;

/**
 * Class Response
 * @package app\lib
 *
 * Class for preparing response output data
 */
class Response
{
    //variables of result marker
    const STATUS_SUCCESS = true;
    const STATUS_ERROR   = false;

    /**
     * @param array $data Data for output in JSON-format
     * @param array $params Different additional parameters
     * @return void
     */
    public function outputJson($data, $params = [])
    {
        //mark response data as JSON content
        header('Content-Type: application/json');
        //output response
        echo json_encode($data);
    }

    /**
     * @param string $message Information about error
     * @param array $params Different additional parameters
     * @return void
     */
    public function outputJsonError($message, $params = [])
    {
        $this->outputJson([
            'status'  => self::STATUS_ERROR,
            'message' => $message,
            'result'  => [],
        ], $params);
        exit(0);
    }

    /**
     * @param array $data Data for output in JSON-format
     * @param array $params Different additional parameters
     * @return void
     */
    public function outputJsonSuccess($data, $params = [])
    {
        $this->outputJson([
            'status'  => self::STATUS_SUCCESS,
            'result'  => $data,
        ], $params);
        exit(0);
    }
}
