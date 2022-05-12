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
    /* inserisce in database il logged status
     * 0 o NULL: non loggato
     * 1: loggato 
     * 2: loggato come amministratore
     */
    function setLogged($chatId, $log){
        $db = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);
        $sql = "UPDATE immobot_stato
                SET logged = $log
                WHERE chatId = $chatId";
        $rs = $db->query($sql);

        $db->close();
    }
    // controlla lo stato di log
    function checkLogged($chatId){
        $db = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);
        $sql = "SELECT *
                FROM immobot_stato
                WHERE chatId = $chatId";
        $rs = $db->query($sql);
        $record = $rs->fetch_assoc();

        $db->close();
        return $record["logged"];
    }
    // setto lo stato ... e lo step
    function setStatus($chatId, $stato, $step){
        // aggiorno database con lo stato attuale
        $db = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);
        $sql = "SELECT *
                FROM immobot_stato
                WHERE chatId = $chatId";
        $rs = $db->query($sql);
        $record = $rs->fetch_assoc();

        // se lo stato va creato, inserisco i dati, se va aggiornato, aggiorno lo stato
        if($record["chatId"] == ""){
            $sql = "INSERT INTO immobot_stato(chatId, stato, step)
                    VALUES ($chatId, '$stato', $step)";
        }else{
            $sql = "UPDATE immobot_stato
                    SET stato = '$stato', step = $step
                    WHERE chatId = $chatId";
        }
        $db->query($sql);

        $db->close();
    }
    // ricevo lo stato di una chat
    function getStatus($chatId){
        $db = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);
        $sql = "SELECT *
                FROM immobot_stato
                WHERE chatId = $chatId";
        $rs = $db->query($sql);
        $record = $rs->fetch_assoc();

        $db->close();
        return $record["stato"];
    }
    // ricevo lo step di una chat
    function getStep($chatId){
        $db = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);
        $sql = "SELECT *
                FROM immobot_stato
                WHERE chatId = $chatId";
        $rs = $db->query($sql);
        $record = $rs->fetch_assoc();

        $db->close();
        return $record["step"];
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

            // file_put_contents("data.json", $keyboardEnc . "\n", FILE_APPEND);
            
            $data = [
                'chat_id' => $chatId,
                'text' => $msg,
                'reply_markup' => $keyboardEnc
            ];

            $url = $this->setUrl("sendMessage?".http_build_query($data));
            fetchApi($url);
        }

        function forceReply($chatId){

            $keyboradsValue = array(
                array("button 1","button 2"),
                array("button 3","button 4"),
             );
             $replyMarkup = array(
               'keyboard' => $keyboradsValue,
               'force_reply' => true,
               'selective' => true
             );

            $encodedMarkup = json_encode($replyMarkup, true);

            $data = [
                'chat_id' => $chatId, 
                'text' => 'force reply',
                'reply_markup' => $encodedMarkup
            ];

            // richiesta per inviare il messaggio
            $url = $this->setUrl("sendMessage?".http_build_query($data));

            fetchApi($url);
        }
    }

    // trattamento json
    class jsonHandler extends Telegram{
        // ricavo il json di una richiesta e lo ritorno decodificato in variabile php
        function getWebhookJson(){
            $json = file_get_contents("php://input");

            /* file_put_contents("data.log", $json."\n", FILE_APPEND); */

            return json_decode($json, true);
        }
        // prendo l'id chat quando arriva un messaggio
        function getChatId($jsonDecoded){
            return $jsonDecoded["message"]["chat"]["id"];
        }
        // prendo il testo di un messaggio
        function getText($jsonDecoded){
            return $jsonDecoded["message"]["text"];
        }
        // prendo l'id chat quando viene premuto un bottone
        function getCallbackChatId($jsonDecoded){
            return $jsonDecoded["callback_query"]["message"]["chat"]["id"];
        }
        // prendo i dati di ritorno di un bottone
        function getCallbackData($jsonDecoded){
            return $jsonDecoded["callback_query"]["data"];
        }
    }
?>