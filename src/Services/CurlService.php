<?php

namespace Metawesome\KongPublish\Services;

class CurlService {

    /**
     * Curl Object
     * @var object
     */
    private $curl;
    
    /**
     * Url to execute curl
     * @var string
     */
    private $url;

    /**
     * Array with dados to send
     * @var array
     */
    private $data;
    
    public function __construct()
    {
        $this->curl = curl_init();
    }

    public function to($url)
    {
        curl_setopt($this->curl, CURLOPT_URL, $url);
    }

    public function withData($data = [])
    {
        curl_setopt($this->curl, CURLOPT_POSTFIELDS,
                 http_build_query($data));
    }

    public function withJsonData($data = [])
    {
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS,
                 json_encode($data));
    }

    public function post()
    {
        $this->setPostParams();
        return $this->send();
    }

    public function put()
    {
        $this->setCustomRequest('PUT');
        return $this->send();
    }
    
    public function patch()
    {
        $this->setCustomRequest('PATCH');
        return $this->send();
        
    }
    
    public function delete()
    {
        $this->setCustomRequest('DELETE');
        return $this->send();     
    }

    private function send()
    {
        $response = curl_exec($this->curl);
        
        curl_close($this->curl);
        
        if(!$response) {
            return [];
        }

        return json_decode($response, true);
    }

    private function setCurlConfig()
    {
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
    }

    private function setPostParams()
    {
        $this->setCurlConfig();
        curl_setopt($this->curl, CURLOPT_POST, 1);
    }

    private function setCustomRequest($payload)
    {
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $payload);
    }
}