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
        // getMe
        function getMe(){
            $url = $this->setUrl("getMe");
            fetchApi($url);
        }
        // getUpdates
        function getUpdates(){
            $url = $this->setUrl("getUpdates");
            fetchApi($url);
        }
        // invio di un messaggio
        function sendMessage($chatId, $msg){
            // dati per inviare il messaggio
            $data = [
                'chat_id' => $chatId, 
                'text' => $msg
            ];

            // richiesta per inviare il messaggio
            $url = $this->setUrl("sendMessage?".http_build_query($data));
            fetchApi($url);

            /* $response = file_get_contents($url); */
        }

        /* invio di una tastiera con bottoni: numero bottoni e colonne semiautomatici
        /* argomenti della funzione: 
            id chat,
            array con i text dei bottoni,
            array con le callback data dei bottoni,
            numero di colonne desiderate
        */
        function sendKeyboard($chatId, $textArray, $callbackArray, $col, $msg){

            $buttonNumber = count($textArray);
            // tastiera di partenza, si presenta con una riga vuota senza bottoni
            $keyboard = [
                'inline_keyboard' => [
                    [

                    ]
                ]
            ];

            //  creazione della tastera
            for($i=1, $j=0; $i<=$buttonNumber; $i++){
                
                // aggiunta del bottone alla tastiera
                $toPush = array('text' => $textArray[$i-1], 'callback_data' => $callbackArray[$i-1]);
                array_push($keyboard["inline_keyboard"][$j], $toPush);

                // nuova riga
                if($i % $col == 0){
                    array_push($keyboard['inline_keyboard'], array());
                    $j++;
                }
            }

            // encoding in json e invio della tastiera
            $keyboardEnc = json_encode($keyboard);

            file_put_contents("data.json", $keyboardEnc . "\n", FILE_APPEND);
            
            $data = [
                'chat_id' => $chatId,
                'text' => $msg,
                'reply_markup' => $keyboardEnc
            ];

            $url = $this->setUrl("sendMessage?".http_build_query($data));
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
        // ottenimeno dei dati dal bottone premuto (NON ULTIMATO)
        function getCallbackData($jsonDecoded){
            return $jsonDecoded["callback_query"]["data"];
        }
    }
?>