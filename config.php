<?php
    function fetchApi($url){
        $req = curl_init($url);

        $resp = curl_exec($req);

        if($resp == false){
            $error = curl_error($req);
            curl_close($req);
            throw new ErrorException($error);
        }
        else{
            curl_close($req);
            //return $resp;
        }
    }

    class Telegram{
        protected $token;

        function __construct($token){
            $this->token = $token;
        }
        function setUrl($methodName){
            $url = "https://api.telegram.org/bot".$this->token."/".$methodName;
            return $url;
        }
        function getMe(){
            $url = $this->setUrl("getMe");
            fetchApi($url);
        }
    }
?>