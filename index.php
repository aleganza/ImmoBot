<?php
    require('token.php'); // inserire token del bot telegram
    require('config.php');

    try{
        $ngrokUrl = "https://a2b0-82-52-13-195.ngrok.io";
        
        $bot = new Telegram($token);
        $jH = new jsonHandler($token);

        var_dump($bot->setWebhook($ngrokUrl));
        // ottengo array del json e l'id della chat
        $webhookJson = $jH->getWebhookJson();
        $chatId = $jH->getChatId($webhookJson);

        // switch case per le risposte del bot
        $msg = $jH->getText($webhookJson) !== "" ? $jH->getText($webhookJson) : "";

        switch($msg){
            // qualunque messaggio
            default: {
                $bot->sendMessage($chatId, 'non valido');
                break;
            }
            // se il comando è /prova
            case '/prova': {
                $bot->sendMessage($chatId, 'hai eseguito /prova');
                break;
            }
        }
        
        // mando un messaggio alla chat da cui proviene il comando
       /*  $bot->sendMessage($chatId, $msg); */
        
    }catch(ErrorException $e){
        echo $e->getMessage();
    }
?>