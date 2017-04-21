<?php

include_once "NetUtil.php";
include_once "DES.php";

const  LOGIN_URL = "https://api.codoon.com/token?email=%s&password=%s&grant_type=password&client_id=099cce28c05f6c39ad5e04e51ed60704&scope=user";
const  CARIFY_URL = "https://api.codoon.com/api/verify_credentials";



class CodoonSport{
    var $username;
    var $password;
    var $request;

    var $token = "Basic MDk5Y2NlMjhjMDVmNmMzOWFkNWUwNGU1MWVkNjA3MDQ6YzM5ZDNmYmVhMWU4NWJlY2VlNDFjMTk5N2FjZjBlMzY=";
    var $timestamp = 0;
    var $refresh_token = "0";
    var $user_id = "0";
    var $headers ;

    function __construct($username, $password) {
        $this->username = $username;
        $this->password = $password;
        $this->request = new Request();
        $this->headers = array('User-Agent: CodoonSport(7.13.0 680;Android 4.2.2;samsung GT-S7562)',
            'Accept-Encoding: deflate',"Authorization: $this->token","Timestamp: $this->timestamp",
            "Gaea: cb71db89628d768d185950b4afe6bf76",
            "Uranus: A1gr5gDL1TFfN4CTu0XQ2EYpzQG9jU+Qh3Qd3FW0K+2mF6WIiJR28Wev5iIuvsLe",
            "did: 24-864895024806919","Davinci: 350139028703096","Connection: Keep-Alive"
        );
    }

    public function setToken($token){
        $this->token = "Bearer ".$token;
    }
    public function setRefreshToken($token){
        $this->refresh_token = $token;
    }

    public function setUid($uid){
        $this->user_id = $uid;
    }
    public function setTimestamp($timestamp){
        $this->timestamp = $timestamp;
    }

    public function Login(){
        $lUrl = sprintf(LOGIN_URL,$this->username,$this->password);
        $this->AddHeaderPorperty();
        $result = $this->request->get($lUrl, $this->headers);
        return $result;
    }

    private function AddHeaderPorperty(){
        $this->headers[2] = "Authorization: $this->token";
        $this->headers[3] = "Timestamp: $this->timestamp";
        $gaea = md5(LOGIN_URL."^0^".$this->refresh_token."^".$this->timestamp);
        $this->headers[4] = "Gaea: ".$gaea;
        $this->headers[5] = "Uranus: ".(new DES())->encrypt(md5($gaea."^".$this->timestamp."^350139028703096"));
    }


    public function GetMyInfomation(){
        $this->AddHeaderPorperty();
        return $this->request->get(CARIFY_URL,$this->headers);
    }

}