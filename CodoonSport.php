<?php

include_once "NetUtil.php";
const  LOGIN_URL = "https://api.codoon.com/token?email=%s&password=%s&grant_type=password&client_id=099cce28c05f6c39ad5e04e51ed60704&scope=user";
const  CARIFY_URL = "https://api.codoon.com/api/verify_credentials";



class CodoonSport{
    var $username;
    var $password;
    var $request;


    function __construct($username, $password) {
        $this->username = $username;
        $this->password = $password;
        $this->request = new Request();
    }

    public function setToken($token){
        $this->request->setToken("Bearer ".$token);
    }
    public function setTimestamp($timestamp){
        $this->request->setTimestamp($timestamp);
    }

    public function Login(){
        $lUrl = sprintf(LOGIN_URL,$this->username,$this->password);
        $result = $this->request->get($lUrl);
        return $result;
    }


    public function GetMyInfomation(){
        return $this->request->get(CARIFY_URL);
    }

}