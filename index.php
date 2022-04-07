<?php
    require('config.php');

    try{
        $token = "5100404136:AAHEr_gwp5d-q6HXP3c9ls-2D_CC0H7WI_w"; //token
        $bot = new Telegram($token);
    
        $bot->getMe();
    }catch(ErrorException $e){
        echo $e->getMessage();
    }
?>