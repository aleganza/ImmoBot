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
        protected $token; // token bot
        protected $tUrl; // url di telegram api

        function __construct($token){
            /* $this->token = $token; */
            $this->tUrl = "https://api.telegram.org/bot".$token; // url a cui fare le richieste
        }
        function setUrl($method){
            $url = $this->tUrl."/".$method;
            return $url;
        }
        function getMe(){
            $url = $this->setUrl("getMe");
            fetchApi($url);
        }
        function getUpdates(){
            $url = $this->setUrl("getUpdates");
            fetchApi($url);
        }
        function getDataJson($method){
            $url = $this->setUrl($method);

            $json = file_get_contents($url);
            file_put_contents("data.log", $json."\n", FILE_APPEND);

            return json_decode($json, true); // ritorno il json come variabile php
        }
        function sendMessage($chatId){
            // dati per inviare il messaggio
            $data = [
                'chat_id' => $chatId, 
                'text' => "funziona"
            ];

            // richiesta per inviare il messaggio
            $url = $this->setUrl("sendMessage?".http_build_query($data));

            /* $response = file_get_contents($url); */
        }
    }
?>