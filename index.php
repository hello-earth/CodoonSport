<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/20
 * Time: 15:24
 */

date_default_timezone_set('Asia/Shanghai');

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
            $result = $cSport->UploadSteps(1623);
            $resultArray = json_decode($result, true);
            echo $resultArray["status"];
        }
    }
}
