<?php
    require('config.php');

    try{
        $token = "5100404136:AAHEr_gwp5d-q6HXP3c9ls-2D_CC0H7WI_w"; //token
        $ngrokUrl = "https://0dd0-82-52-13-195.ngrok.io";
        
        $bot = new Telegram($token);

        /* $bot->getUpdates(); */
        
        $url = $bot->setWebHook($ngrokUrl);

        $update = file_get_contents('php://input');
        $update = json_decode($update, TRUE);

    }catch(ErrorException $e){
        echo $e->getMessage();
    }
?>