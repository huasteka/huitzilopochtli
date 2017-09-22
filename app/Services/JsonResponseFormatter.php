<?php
namespace App\Services;

class JsonResponseFormatter
{

    private $data;

    private $meta;

    private $errors;

    public function __construct($data = null, $meta = null, $errors = null)
    {
        $this->data = $data;
        $this->meta = $meta;
        $this->errors = $errors;
    }

    public function getData()
    {
        return $this->data;
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
        if (!empty($this->data)) {
            $response = array_merge($response, ['data' => $this->data]);
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