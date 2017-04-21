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
$resultArray = json_decode($result, true);

if(isset($resultArray["access_token"])){
    $token = $resultArray["access_token"];
    if(strlen($token)>10) {
        $result = $cSport->GetMyInfomation();
        $resultArray = json_decode($result, true);
        if(strlen($resultArray["id"])==36){
            var_dump($resultArray);
        }
    }
}