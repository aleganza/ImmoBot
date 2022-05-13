<?php

    $text = $jH->getText($webhookJson);
    $db = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);

    if($step == 0){
        $sql = "SELECT *
                FROM immobot_proprietari
                WHERE CF = '$text'";
        $rs = $db->query($sql);

        // check presenza codice fiscale
        if($rs->num_rows != 0){
            
            $bot->sendMessage($statusChatId, "Inserisci password");
            setStatus($statusChatId, "login", 1);

        }else{
            $bot->sendMessage($statusChatId, "❌ Codice fiscale non presente");
        }
    }
    if($step == 1){
        $sql = "SELECT *
                FROM immobot_proprietari
                WHERE password = '$text'";
        $rs = $db->query($sql);

        // check presenza password
        if($rs->num_rows != 0){
            $bot->sendMessage($statusChatId, "✅ Login avvenuto!");
            setLogged($statusChatId, 1);
            $bot->sendMessage($statusChatId, "➡️ Ora puoi eseguire /funzioni");
        }
    }
?>