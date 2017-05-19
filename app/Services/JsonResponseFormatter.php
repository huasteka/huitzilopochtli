<?php
namespace App\Services;

class JsonResponseFormatter
{

    private $result;

    private $meta;

    private $errors;

    public function __construct($result = null, $meta = null, $errors = null)
    {
        $this->result = $result;
        $this->meta = $meta;
        $this->errors = $errors;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function getMeta()
    {
        return $this->meta;
    }

    public function getErrors()
    {
        return $this->errors;
    }
    
    public function toArray()
    {
        $response = [];
        if (!empty($this->result)) {
            $response = array_merge($response, ['result' => $this->result]);
        }
        if (!empty($this->meta)) {
            $response = array_merge($response, ['meta' => $this->meta]);
        }
        if (!empty($this->errors)) {
            $response = array_merge($response, ['errors' => $this->meta]);
        }
        return $response;
    }

}