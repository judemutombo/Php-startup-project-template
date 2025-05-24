<?php

namespace API\Configuration\Transaction;


class Request{
    private $body = [];
    private $params = [];
    private $query = [];
    private $headers;
    private $url = "";
    private $method = "";

    public function __construct($method, $body, $components, $url, $headers){
        $this->method = $method;
        $this->body = $body;
        $this->url = $url;
        $this->headers = $headers;
    }

    public function __get($name){
        if(isset($this->$name))
            return $this->$name;
        return null;
    }
}