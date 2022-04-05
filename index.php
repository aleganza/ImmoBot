<?php
    $telegram = "https://api.telegram.org/bot";
    $token = "5100404136:AAHEr_gwp5d-q6HXP3c9ls-2D_CC0H7WI_w";
    $url = $telegram.$token."/getMe";

    $request = curl_init($url);

    curl_exec($request);




?>