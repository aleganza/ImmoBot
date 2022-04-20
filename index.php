<?php
    require('token.php'); // inserire token del bot telegram
    require('config.php');

    try{
        $ngrokUrl = "https://5e56-82-52-13-195.ngrok.io";
        
        $bot = new Telegram($token);
        $jH = new jsonHandler($token);

        var_dump($bot->setWebhook($ngrokUrl));
        // ottengo array del json e l'id della chat
        $webhookJson = $jH->getWebhookJson();
        $chatId = $jH->getChatId($webhookJson);

        // switch case per le risposte del bot
        $msg = $jH->getText($webhookJson) !== "" ? $jH->getText($webhookJson) : "";

        switch($msg){
            // se il comando non esiste / se non è un comando
            default: {
                if ($msg[0] == '/')
                    $bot->sendMessage($chatId, 'Comando non esistente');
                
                break;
            }
            // se il comando è /prova
            case '/prova': {
                /* $bot->sendMessage($chatId, 'hai eseguito /prova'); */
                $bot->sendKeyboard($chatId, 'bottone di prova');
                break;
            }
            case '/help': {
                $msg = 'Comandi disponibili:'.PHP_EOL.'/help - Lista dei comandi disponibili'.PHP_EOL.'/prova - prova';
                $bot->sendMessage($chatId, $msg);
                break;
            }
        }
        
        // mando un messaggio alla chat da cui proviene il comando
       /*  $bot->sendMessage($chatId, $msg); */
        
    }catch(ErrorException $e){
        echo $e->getMessage();
    }
?>