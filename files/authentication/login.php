<?php

    /* login
     * richiede username e password tramite il meccanismo status - step
     */

    $text = $jH->getText($webhookJson);
    $db = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);

    if($step == 0){
        $sql = "SELECT *
                FROM immobiliare_proprietari
                WHERE CF = '$text'";
        $rs = $db->query($sql);

        // check presenza codice fiscale
        if($rs->num_rows != 0){
            $bot->sendMessage($statusChatId, "👇 Inserisci password");
            setStatus($statusChatId, "login", 1);

        }else{
            $bot->sendMessage($statusChatId, "❌ Codice fiscale non presente");
            $bot->sendMessage($statusChatId, "👇 Inserisci codice fiscale");
        }
    }
    if($step == 1){
        $text = md5($text); // codifica in hash md5 della password
        $sql = "SELECT *
                FROM immobiliare_proprietari
                WHERE password = '$text'";
        $rs = $db->query($sql);

        // check presenza password
        if($rs->num_rows != 0){
            setLogged($statusChatId, 1);
            setStatus($statusChatId, "start", 0);
            $bot->sendMessage($statusChatId, "✅ Login avvenuto!".PHP_EOL."➡️ Ora puoi eseguire /functions");
        }else{
            $bot->sendMessage($statusChatId, "❌ Password errata");
            $bot->sendMessage($statusChatId, "👇 Inserisci password");
        }
    }
?>