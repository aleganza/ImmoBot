<?php
    // il tuo token
    $token = "5100404136:AAHEr_gwp5d-q6HXP3c9ls-2D_CC0H7WI_w";
    $url = "https://api.telegram.org/bot".$token."/getUpdates";

    $request = curl_init($url);

    curl_exec($request);


?>