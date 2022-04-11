<?php
    // manda la richiesta al server di telegram
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

    // richieste al server telegram
    class Telegram{
        protected $tUrl; // url di telegram api

        function __construct($token){
            /* $this->token = $token; */
            $this->tUrl = "https://api.telegram.org/bot".$token; // url a cui fare le richieste
        }
        // prepara l'url di richiesta
        function setUrl($method){
            return $this->tUrl."/".$method;
        }

        function getMe(){
            $url = $this->setUrl("getMe");
            fetchApi($url);

           
        }

        function getUpdates(){
            $url = $this->setUrl("getUpdates");
            fetchApi($url);
        }
        
        function sendMessage($chatId){
            // dati per inviare il messaggio
            $data = [
                'chat_id' => $chatId, 
                'text' => "funziona"
            ];

            // richiesta per inviare il messaggio
            $url = $this->setUrl("sendMessage?".http_build_query($data));

            fetchApi($url);

            /* $response = file_get_contents($url); */
        }

        function setWebHook ($ngrokUrl){
            $data = [
                'url' => $ngrokUrl
            ];

            $url = $this->setUrl("setWebHook?".http_build_query($data));
            fetchApi($url);

            $this->getJson($url);
        }
        // prendi il json di una richiesta
        function getJson($url){

            $json = file_get_contents($url);
            file_put_contents("data.log", $json."\n", FILE_APPEND);

            return json_decode($json, true); // ritorno il json come variabile php
        }

        function getChatId($json){
            return $json["result"][0]["message"]["chat"]["id"];
        }
    }
?>