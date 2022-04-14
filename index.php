<?php
    require('token.php'); // inserire token del bot telegram
    require('config.php');

    try{
        $ngrokUrl = "https://d80f-82-52-13-195.ngrok.io";
        
        $bot = new Telegram($token);
        $jsonHandler = new jsonHandler($token);

        var_dump($bot->setWebhook($ngrokUrl."/json_handler.php"));
        $chatId = $jsonHandler->getChatId($json);

        file_put_contents("data.log", $chatId, FILE_APPEND);
    }catch(ErrorException $e){
        echo $e->getMessage();
    }
?>