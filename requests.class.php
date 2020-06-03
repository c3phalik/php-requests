<?php

class requests{

    public $headers_to_send = array( // This property is used to build a curl request headers. Referenced in the init_request method.
        "user_agent" => "Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0"
    );

    public $response; // This property stores the response body received from executed requests
    public $request_headers; // This property stores request headers for executed requests

    //Builds a curl request with the desired configuration. It is referenced in public methods like get, post....
    private function init_request($url, $method='get', $payload=null){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url); // Refer https://www.php.net/manual/en/function.curl-setopt.php for CURLOPT_{CONSTANT} documentation
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->headers_to_send["user_agent"]);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        
        //Need to add logic for more http methods.
        if ($method == 'post'){
            curl_setopt($ch, CURLOPT_POST, true);
            return $ch;
        } else {
            return $ch;
        }
        
    }

    // curl_getinfo() returns an associative array of requst headers(https://www.php.net/manual/en/function.curl-getinfo.php).
    // The following function maps each {key, value} pair as a public property  key = value;
    // this dynamically creates object properties.
    private function dynamic_header_properties($ch){
        $this->request_headers = curl_getinfo($ch);
        foreach($this->request_headers as $key => $value){
            $this->{$key} = $value;
        }
    }

    // This function handles sends & handles logic for each request. Implemented for brevity.
    private function submit_request($ch){ 
        $this->response = curl_exec($ch);
        $this->dynamic_header_properties($ch);
        return $this->response;
    }
    
    // Simple get request
    public function get($url){
        $ch = $this->init_request($url);
        $this->submit_request($ch);
        return $this->response;
    }

    // Simple post request
    public function post($url){
        $ch = init_request($url, 'post');
        $this->submit_request($ch);
        return $this->response;
    }
    

}
