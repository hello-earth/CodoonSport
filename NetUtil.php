<?php

class Request {



    function __construct($token = "Basic MDk5Y2NlMjhjMDVmNmMzOWFkNWUwNGU1MWVkNjA3MDQ6YzM5ZDNmYmVhMWU4NWJlY2VlNDFjMTk5N2FjZjBlMzY=",
                         $timestamp = "0"){
        $this->token = $token;
        $this->timestamp = $timestamp;
    }

    public function get($url, $headers){
        $result="";
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

    public function post($url, $headers, $post_data){
        $result="";
        try {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  //SSL 报错时使用
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  //SSL 报错时使用
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            $result = curl_exec($ch);
            curl_close($ch);
        }catch (Exception $ex){
            print_r($ex);
        }
        return $result;
    }

    public static  function getSubString($content,$first,$end,$method=1){
        $firstindex = strpos($content,$first);
        if($firstindex){
            $firstindex += strlen($first);
            $endindex = false;
            $endindex = false;

            if($method)
                $endindex = strpos($content,$end,$firstindex);
            else
                $endindex = strrpos($content,$end);
            if($endindex){
                return substr($content,$firstindex,$endindex-$firstindex);
            }
        }
    }

}

?>