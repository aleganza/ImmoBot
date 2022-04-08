<?php
    require('config.php');

    try{
        $token = "5100404136:AAHEr_gwp5d-q6HXP3c9ls-2D_CC0H7WI_w"; //token
        $bot = new Telegram($token);
    
        /* $bot->getMe(); */
        $bot->getUpdates();

        $dataJson = $bot->getDataJson("getUpdates");
        $idChat = $dataJson["result"][0]["message"]["chat"]["id"];  // DA CHIEDERE AL GCAN SE SI PUO FARE MENO INCASINATO

        $bot->sendMessage($idChat);

    }catch(ErrorException $e){
        echo $e->getMessage();
    }
?>