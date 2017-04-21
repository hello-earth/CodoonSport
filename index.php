<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/20
 * Time: 15:24
 */

include_once "CodoonSport.php";

$cSport = new CodoonSport("","");
$result = $cSport -> Login();
$result = Request::getSubString($result,"{","}");

$resultArray = json_decode("{".$result."}", true);
var_dump($resultArray);

if(isset($resultArray["access_token"])){
    $token = $resultArray["access_token"];
    if(strlen($token)>10) {
        $cSport->setToken($token);
        $cSport->setTimestamp($resultArray["timestamp"]);
        $cSport->setRefreshToken($resultArray["refresh_token"]);
        $cSport->setUid($resultArray["user_id"]);
        echo $cSport->GetMyInfomation();
    }
}