<?php

class Request {

    var $token;
    var $timestamp;

    function __construct($token = "Basic MDk5Y2NlMjhjMDVmNmMzOWFkNWUwNGU1MWVkNjA3MDQ6YzM5ZDNmYmVhMWU4NWJlY2VlNDFjMTk5N2FjZjBlMzY=",
                         $timestamp = "0"){
        $this->token = $token;
        $this->timestamp = $timestamp;
    }


    public function setToken($token){
        $this->token = $token;
    }

    public function setTimestamp($timestamp){
        $this->timestamp = $timestamp;
    }

    public function get($url){
        $headers = array('User-Agent: CodoonSport(7.13.0 680;Android 4.2.2;samsung GT-S7562)',
            'Accept-Encoding: deflate',"Authorization: $this->token","Timestamp: $this->timestamp",
            "Gaea: cb71db89628d768d185950b4afe6bf76",
            "Uranus: A1gr5gDL1TFfN4CTu0XQ2EYpzQG9jU+Qh3Qd3FW0K+2mF6WIiJR28Wev5iIuvsLe",
            "did: 24-864895024806919","Davinci: 1","Connection: Keep-Alive"
        );
        $result="";
        if("https://api.codoon.com/api/verify_credentials"==$url)
            var_dump($headers);

        try{
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  //SSL 报错时使用
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  //SSL 报错时使用
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $result = curl_exec($ch);
            curl_close($ch);
        }catch (Exception $ex){
            print_r($ex);
        }
        return $result;
    }

    public static  function getSubString($content,$first,$end){
        $firstindex = strpos($content,$first);
        if($firstindex){
            $firstindex += strlen($first);
            $endindex = strpos($content,$end,$firstindex);
            if($endindex){
                return substr($content,$firstindex,$endindex-$firstindex);
            }
        }
    }

}

?>