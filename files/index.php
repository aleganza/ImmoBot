<?php
    require('token.php'); // inserire token del bot telegram
    require('config.php'); // funzioni base
    require('../assets/databaseFunctions.php'); // funzioni per database

    // controlla se sono loggato
    function checkLogged($chatId){
        $db = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);
        $sql = "SELECT logged
                FROM immobot_stato
                WHERE chatId = $chatId";
        $rs = $db->query($sql);
        $record = $rs->fetch_assoc();

        $db->close();
        return $record["logged"];
    }
    // setto lo stato ... e lo step
    function setStatus($chatId, $stato){
        // aggiorno database con lo stato attuale
        $db = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);
        $sql = "SELECT *
                FROM immobot_stato
                WHERE chatId = $chatId";
        $rs = $db->query($sql);
        $record = $rs->fetch_assoc();

        // se lo stato va creato, inserisco i dati, se va aggiornato, aggiorno lo stato
        if($record["chatId"] == ""){
            $sql = "INSERT INTO immobot_stato(chatId, stato)
                    VALUES ($chatId, '$stato')";
        }else{
            $sql = "UPDATE immobot_stato
                    SET stato = '$stato'
                    WHERE chatId = $chatId";
        }
        $db->query($sql);

        $db->close();
    }

    /* file_put_contents("data.log", $var . "\n", FILE_APPEND); */

    try{
        $ngrokUrl = "https://b47a-79-24-39-44.ngrok.io"; // inserire url di ngrok
        
        $bot = new Telegram($token);
        $jH = new jsonHandler($token);

        $bot->setWebhook($ngrokUrl);
        // ottengo json dal webhook
        $webhookJson = $jH->getWebhookJson();

        // switch case per i comandi del bot
        $chatId = $jH->getChatId($webhookJson);
        $command = $jH->getText($webhookJson);

        // switch case per le operazioni del bot
        $callbackChatId = $jH->getCallbackChatId($webhookJson);
        $callback = $jH->getCallbackData($webhookJson);

        switch($callback){
            // autenticazione
            case 'login': {
                setStatus($callbackChatId, "login");
                
                break;
            }
            case 'registrati': {
                setStatus($callbackChatId, "registrati");

                break;
            }
            case 'amministratore': {
                setStatus($callbackChatId, "amministratore");
                
                break;
            }

            // operazioni
            case 'proprietari': {
                $bot->sendMessage($callbackChatId, 'hai premuto proprietari');
                
                // check logged
                $check = checkLogged($callbackChatId);
                if($check != 1) 
                    break;

                break;
            }
            case 'immobili': {
                $bot->sendMessage($CallbackchatId, 'hai premuto immobili');
                
                break;
            }
        }

        switch($command){
            case '/prova': {
                
                

                break;
            }
            case '/start': {
                // aggiorno database con lo stato attuale
                setStatus($chatId, "start");
                
                $textArray = array(
                    'Login',
                    'Registrati',
                    'Entra come amministratore'
                );
                $callbackArray = array(
                    'login',
                    'registrati',
                    'amministratore'
                );

                $bot->sendKeyboard($chatId, $textArray, $callbackArray, 2, "Registrazione");

                $db->close();
                break;
            }
            case '/go': {
                $textArray = array(
                    '🤵🏻‍♂️ Proprietari', 
                    '🏢 Immobili', 
                    '💰 Intestazioni',
                    '📍 Zone',
                    '🕹 Tipologie'
                );
                $callbackArray = array(
                    'proprietari', 
                    'immobili',
                    'intestazioni',
                    'zone',
                    'tipologie'
                );
                $buttonNumber = count($textArray);
                $bot->sendKeyboard($chatId, $textArray, $callbackArray, 2, "Dove vuoi andare?");
                
                break;
            }
            case '/proprietari': {
                $bot->sendMessage($chatId, 'te pigia proprietari');
                $bot->sendMessage($chatId, $callback);
                break;
            }
            case '/help': {
                $msg = 'Comandi disponibili:'.PHP_EOL.'/go - Fai partire il bot'.PHP_EOL.'/help - Lista dei comandi disponibili';
                $bot->sendMessage($chatId, $msg);
                break;
            }
            case '/credit': {
                $bot->sendMessage($chatId, 'made by Alessio Ganzarolli');

                break;
            }
            // se il comando non esiste, se non è un comando
            default: {
                if ($command[0] == '/')
                    $bot->sendMessage($chatId, 'Comando non esistente');

                break;
            }
        }
    }catch(ErrorException $e){
        echo $e->getMessage();
    }
?>