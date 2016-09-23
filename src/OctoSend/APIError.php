<?php

namespace OctoSend;

class APIError extends \Exception
{
    public function __construct($http_version, $http_status_code, $http_status, $http_headers, $body)
    {
        $this->http_version = $http_version;
        $this->http_status_code = $http_status_code;
        $this->http_status = $http_status;
        $this->http_headers = $http_headers;
        $this->body = $body;

        parent::__construct("Octosend API return HTTP code " . $this->http_status_code .": \"".$this->http_status."\"", $this->http_status_code);
    }
}
