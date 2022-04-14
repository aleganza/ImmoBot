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
        // getme
        function getMe(){
            $url = $this->setUrl("getMe");
            fetchApi($url);
        }
        // getupdates
        function getUpdates(){
            $url = $this->setUrl("getUpdates");
            fetchApi($url);
        }
        // invio di un messaggio
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
        // setting webhook
        function setWebhook ($ngrokUrl){
            $data = [
                'url' => $ngrokUrl
            ];

            $url = $this->setUrl("setWebhook?".http_build_query($data));
            fetchApi($url);
        }
        // eliminazione webhook
        function deleteWebhook(){
            $url = $this->setUrl("deleteWebhook");
            fetchApi($url);
        }
    }

    // tutte le operazioni eseguibili sui json
    class jsonHandler extends Telegram{
        // prendi il json di una richiesta (vecchio json_handler.php)
        function getWebhookJson(){
            $json = file_get_contents("php://input");

            /* file_put_contents("data.log", $json."\n", FILE_APPEND); */

            return json_decode($json, true);
        }
        // prendi l'id di una chat dal json (webhook)
        function getChatId($jsonDecoded){
            return $jsonDecoded["message"]["chat"]["id"];
        }
        // prendi il testo di un messaggio dal json (webhook)
        function getText($jsonDecoded){
            return $jsonDecoded["message"]["text"];
        }
    }
?>