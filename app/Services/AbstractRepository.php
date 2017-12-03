<?php

namespace App\Services;

abstract class AbstractRepository
{

    protected $requestReader;

    /**
     * @param RequestReader $requestReader
     */
    public function __construct($requestReader)
    {
        $this->requestReader = $requestReader;
    }

    /**
     * @return RequestReader
     */
    protected function getRequestReader()
    {
        return $this->requestReader;
    }
    
    protected function getByKey($collection, $key, $default) {
        return isset($collection[$key]) ? $collection[$key] : $default;
    }

}
