<?php
namespace App\Esi;

use GuzzleHttp\Client;
class Api {
    private $guzzle;
    private $token;
    private $last;

    public function __construct()
    {
        $this->guzzle = new Client([
            'base_uri'=>"https://esi.tech.ccp.is"
        ]);
    }

    public function guzzle()
    {
        return $this->guzzle;
    }

    public function call($method,$url,$fields = null)
    {
        $options = ['headers'=>[]];
        if ($this->token) {
            $options['headers']['Authorization'] = 'Bearer '.$this->token;
        }
        if (strtolower($method) == 'post') {
            $options['headers']['Accept'] = 'application/json';
            $options['headers']['Content-Type'] = 'application/json';
            $options['json'] = $fields;
            $options['debug'] = true;
            echo $method . ' - ' .$url;
            echo '<pre>';
            $request = $this->guzzle->request('POST',$url,$options);
            echo '</pre>';
            die;
        } else {
            $options['query'] = $fields;
            $request = $this->guzzle->request($method, $url,$options);
        }
        $this->last = $request;
        return json_decode($request->getBody(),1);
    }

    public function last()
    {
        return $this->last;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

}
