<?php

// This whole class has been my base controller for projects for some time
// I can't remember where it came from
class BaseController
{
    // __call magic method. 
    public function __call($name, $arguments): array
    {
        $this->sendOutput('', array('HTTP/1.1 404 Not Found'));
    }

    // Get URI elements
    protected function getUriSegments(): array
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = explode( '/', $uri );
        return $uri;
    }

    // Get querystring elements
    protected function getQueryStringParams(): array
    {
        parse_str($_SERVER['QUERY_STRING'], $query);
        return $query;
    }

    // Output
    protected function sendOutput(mixed $data, array $httpHeaders=[])
    {
        header_remove('Set-Cookie');
        if (is_array($httpHeaders) && count($httpHeaders)) {
            foreach ($httpHeaders as $httpHeader) {
                header($httpHeader);
            }
        }
        echo $data;
        exit;
    }
}