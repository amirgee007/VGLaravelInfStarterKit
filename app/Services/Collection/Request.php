<?php

namespace Vanguard\Services\Collection;


abstract class Request
{
    // Url
    public $url;

    // Request method
    public $method;

    // Header Params
    public $headerParams = array();

    // Request params
    public $udfParams = array();

    // Payload
    public $payload = array();

    // Parser
    public $parser;

    public function __construct($url = '', $method = 'GET')
    {
        if (!empty($url)) {
            $this->url = $url;
        }
        $this->method = $method;
    }

    // get method
    public function getMethod() {
        return $this->method;
    }

    // Add request params
    public function addParams($key, $value) {
        $this->udfParams[$key] = $value;
    }

    // Set payload
    public function setPayload($payload) {
        $this->payload = $payload;
    }

    // Get payload
    public function getPayload() {
        return $this->payload;
    }

}
