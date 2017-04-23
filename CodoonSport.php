<?php

include_once "NetUtil.php";
include_once "DES.php";

const  LOGIN_URL = "https://api.codoon.com/token?email=%s&password=%s&grant_type=password&client_id=099cce28c05f6c39ad5e04e51ed60704&scope=user";
const  CARIFY_URL = "https://api.codoon.com/api/verify_credentials";
const UPLOAD_STEPS = "https://api.codoon.com/api/mobile_steps_upload_detail";


date_default_timezone_set('Asia/Shanghai');


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
        $this->headers = array('User-Agent:CodoonSport(7.13.0 680;Android 4.4.2;samsung GT-I9500)',
            'Accept-Encoding: deflate',"Authorization: $this->token","Timestamp: $this->timestamp",
            "Gaea: cb71db89628d768d185950b4afe6bf76",
            "Uranus: A1gr5gDL1TFfN4CTu0XQ2EYpzQG9jU+Qh3Qd3FW0K+2mF6WIiJR28Wev5iIuvsLe",
            "did: 24-864895024806919","Davinci: 350139028703096","Connection: Keep-Alive"
        );
    }

    private function setToken($token){
        $this->token = "Bearer ".$token;
    }
    private function setRefreshToken($token){
        $this->refresh_token = $token;
    }

    private function setUid($uid){
        $this->user_id = $uid;
    }
    private function setTimestamp($timestamp){
        $this->timestamp = $timestamp;
    }

    private function ParseResult($result){
        $resultArray = json_decode($result, true);
        if(isset($resultArray["access_token"])) {
            $this->setToken($resultArray["access_token"]);
        }
        if(isset($resultArray["timestamp"])) {
            $this->setTimestamp($resultArray["timestamp"]);
        }
        if(isset($resultArray["refresh_token"])) {
            $this->setRefreshToken($resultArray["refresh_token"]);
        }
        if(isset($resultArray["user_id"])) {
            $this->setUid($resultArray["user_id"]);
        }
    }

    private function AddHeaderPorperty($url=LOGIN_URL, $data="0"){
        $this->headers[2] = "Authorization: $this->token";
        $this->headers[3] = "Timestamp: $this->timestamp";
        $gaea = md5($url."^".$data."^".$this->refresh_token."^".$this->timestamp);
        $this->headers[4] = "Gaea: ".$gaea;
        $this->headers[5] = "Uranus: ".(new DES())->encrypt(md5($gaea."^".$this->timestamp."^350139028703096"));
    }


    public function Login(){
        $lUrl = sprintf(LOGIN_URL,$this->username,$this->password);
        $this->AddHeaderPorperty();
        $result = $this->request->get($lUrl, $this->headers);
        $result = Request::getSubString($result,"{","}");
        $result = "{".$result."}";
        $this->ParseResult($result);
        return $result;
    }

    public function GetMyInfomation(){
        $this->AddHeaderPorperty(CARIFY_URL);
        $result =  $this->request->get(CARIFY_URL,$this->headers);
        $result = Request::getSubString($result,"{","}",0);
        $result = "{".$result."}";
        $this->ParseResult($result);
        return $result;
    }

    public function UploadSteps($steps){
        $starthour = date("H");
        $startmin = floor(date("i")/10)-1;
        if($startmin<0){
            $starthour -= 1;
            $startmin = 50;
        }
        elseif ($startmin<10)
            $startmin = $startmin."0";
        elseif ($startmin==0)
            $startmin = $startmin*10;
        $endmin = floor(date("i")/10)*10;
        if ($endmin==0)
            $endmin = "00";

        $endthour = date("H");
        $today = date( "Y-m-d");
        $randSteps = rand(1,2)==1?rand(0-$steps/5,0-$steps/7):rand($steps/5,$steps/7);
        $startSteps = floor($steps / 2)+$randSteps;
        $endSteps =  floor($steps / 2)-$randSteps;
        $startmiller = floor($startSteps*0.66);
        $endmiller = floor($endSteps*0.66);
        $startcalories = 0.0663940814479638*$startmiller;
        $startcalories = sprintf("%.6f", $startcalories);
        $endcalories = 0.0663940814479638*$endmiller;
        $endcalories = sprintf("%.6f", $endcalories);

        $content  =  "{\"content\":[[\"$starthour:$startmin\",$startSteps,$startcalories,$startmiller,600],[\"$endthour:$endmin\",$endSteps,$endcalories,$endmiller,600]],\"date\":\"$today\"}";
        echo $content;
        $this->AddHeaderPorperty(UPLOAD_STEPS,$content);
        $result =  $this->request->post(UPLOAD_STEPS,$this->headers,$content);
        $result = Request::getSubString($result,"{","}",0);
        $result = "{".$result."}";
        $this->ParseResult($result);
        return $result;
    }
}