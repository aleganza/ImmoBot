<?php
    require('token.php'); // inserire token del bot telegram
    require('config.php');

    try{
        $ngrokUrl = "https://d18f-82-52-13-195.ngrok.io";
        
        $bot = new Telegram($token);
        $jH = new jsonHandler($token);

        var_dump($bot->setWebhook($ngrokUrl));
        // ottengo array del json e l'id della chat
        $chatId = $jH->getChatId($jH->getWebhookJson());
        
        // mando un messaggio alla chat da cui proviene il comando
        $bot->sendMessage($chatId);
        
    }catch(ErrorException $e){
        echo $e->getMessage();
    }
?>