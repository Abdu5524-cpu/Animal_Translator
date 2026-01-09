<?php

class BaseController {
     /** 
     * __call magic method. 
     * This method is called when an undefined method is called.
     */
    public function __call($name, $arguments) {
        $this->sendOutput('', array('HTTP/1.1 404 Not Found'));
    }

    /** 
    * Get URI elements. 
    * This method splits the URL into parts.
    *
    * @return array The URI segments.
    */
    protected function getUriSegments() {
        if (isset($_SERVER['REQUEST_URI'])) {
            $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $uri = explode('/', $uri);
            return $uri;
        }
        return []; // Return empty array if URI is malformed or empty
    }

    /** 
    * Get querystring parameters. 
    *
    * @return array The query string parameters as an associative array.
    */
    protected function getQueryStringParams() {
        parse_str($_SERVER['QUERY_STRING'], $query);
        return $query; // Return the parsed query string as an associative array
    }

    /** 
    * Send API output.
    * This method sends the response data to the client.
    * 
    * @param mixed $data The data to send to the client.
    * @param array $httpHeaders The HTTP headers to send.
    */
    protected function sendOutput($data, $httpHeaders = array()) {
        header_remove('Set-Cookie'); // Remove 'Set-Cookie' header if present

        // Set custom headers if provided
        if (is_array($httpHeaders) && count($httpHeaders)) {
            foreach ($httpHeaders as $httpHeader) {
                header($httpHeader);
            }
        }

        // Send the actual data as the response
        echo $data;
        exit; // Terminate the script after sending the response
    }
}
