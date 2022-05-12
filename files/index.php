<?php
    require('token.php'); // inserire token del bot telegram
    require('config.php'); // funzioni base
    require('../assets/databaseFunctions.php'); // funzioni per database

    /* file_put_contents("data.log", $var . "\n", FILE_APPEND); */

    try{
        $ngrokUrl = "https://f417-79-24-39-44.ngrok.io"; // inserire url di ngrok
        
        $bot = new Telegram($token);
        $jH = new jsonHandler($token);

        $bot->setWebhook($ngrokUrl);
        // ottengo json dal webhook come variabile php
        $webhookJson = $jH->getWebhookJson();

        // switch case per i comandi del bot
        $chatId = $jH->getChatId($webhookJson);
        $command = $jH->getText($webhookJson);

        // switch case per le operazioni del bot
        $callbackChatId = $jH->getCallbackChatId($webhookJson);
        $callback = $jH->getCallbackData($webhookJson);

        // prendo il chatId che mi servirà, che sia recuperato da un messaggio o da una callback da bottone
        $statusChatId = isset($chatId) ? $chatId : $callbackChatId;
        // ricevo status e step
        $status = getStatus($statusChatId);
        $step = getStep($statusChatId);

        /* file_put_contents("data.log", "stato: " . $status . "\n", FILE_APPEND);
        file_put_contents("data.log", "step: " . $step . "\n", FILE_APPEND); */

        switch($command){
            case '/start': {
                // aggiorno database con lo stato attuale
                setStatus($chatId, "start", 0);
                
                $textArray = array(
                    '🧾 Login',
                    '📝 Registrati',
                    '🖥 Entra come amministratore'
                );
                $callbackArray = array(
                    'login',
                    'registrati',
                    'amministratore'
                );

                $bot->sendKeyboard($chatId, $textArray, $callbackArray, 2, "Benvenuto su ImmoBot!");

                $db->close();
                break;
            }
            case '/funzioni': {

                if(checkLogged($chatId) == 1){

                    $textArray = array(
                        '🏢 Visualizza immobili',
                        '❌ Logout',
                        
                    );
                    $callbackArray = array(
                        'immobili', 
                        'logout'
                    );
                    $buttonNumber = count($textArray);
                    $bot->sendKeyboard($chatId, $textArray, $callbackArray, 2, "Funzioni");

                }else if(checkLogged($chatId) == 2){
                    $bot->sendMessage($chatId, "loggato come amministratore");
                }else{
                    $bot->sendMessage($chatId, "non loggato");
                }

                break;
            }
            case '/help': {
                $msg = 'Comandi disponibili:'.PHP_EOL.'/go - Fai partire il bot'.PHP_EOL.'/help - Lista dei comandi disponibili';
                $bot->sendMessage($chatId, $msg);
                break;
            }
            case '/credit': {
                $bot->sendMessage($chatId, '© Alessio Ganzarolli');

                break;
            }
            // se il comando non esiste, se non è un comando
            default: {
                if ($command[0] == '/')
                    $bot->sendMessage($chatId, 'Comando non esistente');

                break;
            }
        }

        // bottoni premuti
        switch($callback){
            // autenticazione
            case 'login': {
                setStatus($callbackChatId, "login", 0);
                $bot->sendMessage($callbackChatId, "Inserisci codice fiscale");
                
                break;
            }
            case 'registrati': {
                
                setStatus($callbackChatId, "registrati", 0);
                $bot->sendMessage($callbackChatId, "Inserisci codice fiscale");

                break;
            }
            case 'amministratore': {
                setStatus($callbackChatId, "amministratore", 0);
                
                break;
            }
            case 'logout': {
                setLogged($callbackChatId, 0);
                $bot->sendMessage($callbackChatId, "✅ Logout effettuato");

                break;
            }
        }

        switch($status){
            case 'registrati': {
                require('authentication/registrati.php');

                break;
            }
            case 'login': {
                require('authentication/login.php');

                break;
            }
        }

    }catch(ErrorException $e){
        echo $e->getMessage();
    }
?>