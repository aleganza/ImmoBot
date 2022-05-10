<?php
    require('token.php'); // inserire token del bot telegram
    require('config.php');

    try{
        $ngrokUrl = "https://d8e7-176-242-82-219.eu.ngrok.io"; // inserire url di ngrok
        
        $bot = new Telegram($token);
        $jH = new jsonHandler($token);

        $bot->setWebhook($ngrokUrl);
        // ottengo array del json e l'id della chat
        $webhookJson = $jH->getWebhookJson();
        $chatId = $jH->getChatId($webhookJson);

        // switch case per le risposte del bot

        $msg = $jH->getText($webhookJson);

        $callback = $jH->getCallbackData($webhookJson)[0];
        file_put_contents("data.log", $callback . "\n", FILE_APPEND);

        switch($msg){
            case '/start': {
                $bot->sendMessage($chatId, 'Benvenuto nel bot immobiliare!');
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
                    '1proprietari', 
                    '1immobili', 
                    '1intestazioni',
                    '1zone',
                    '1tipologie'
                );
                $buttonNumber = count($textArray);
                $bot->sendKeyboard($chatId, $textArray, $callbackArray, 2, "Dove vuoi andare?");
                
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
                if ($msg[0] == '/')
                    $bot->sendMessage($chatId, 'Comando non esistente');

                break;
            }
        }
    }catch(ErrorException $e){
        echo $e->getMessage();
    }
?>