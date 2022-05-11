<?php
    $text = $jH->getText($webhookJson);
    $db = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);

    if($step == 0){
        
        $sql = "INSERT INTO immobot_proprietari(CF)
                VALUES ('$text')";
        $rs = $db->query($sql);

        $bot->sendMessage($statusChatId, "Inserisci nome");
        setStatus($statusChatId, "registrati", 1);
    }
    if($step == 1){
        
        $sql = "INSERT INTO immobot_proprietari(nome)
                VALUES ('$text')";
        $rs = $db->query($sql);

        $bot->sendMessage($statusChatId, "Inserisci cognome");
        setStatus($statusChatId, "registrati", 2);
    }
    if($step == 2){
        
        $sql = "INSERT INTO immobot_proprietari(cognome)
                VALUES ('$text')";
        $rs = $db->query($sql);

        $bot->sendMessage($statusChatId, "Inserisci telefono");
        setStatus($statusChatId, "registrati", 3);
    }
    if($step == 3){
        
        $sql = "INSERT INTO immobot_proprietari(telefono)
                VALUES ('$text')";
        $rs = $db->query($sql);

        $bot->sendMessage($statusChatId, "Inserisci email");
        setStatus($statusChatId, "registrati", 4);
    }
    if($step == 4){
        
        $sql = "INSERT INTO immobot_proprietari(email)
                VALUES ('$text')";
        $rs = $db->query($sql);

        $bot->sendMessage($statusChatId, "Inserisci password");
        setStatus($statusChatId, "registrati", 1);
    }
    if($step == 5){
        
        $sql = "INSERT INTO immobot_proprietari(password)
                VALUES ('$text')";
        $rs = $db->query($sql);

        $bot->sendMessage($statusChatId, "Informazioni raccolte!");
        setStatus($statusChatId, "start", 0);
    }

    /* if($step == 1){
        file_put_contents("data.log", "password: " . $jH->getText($webhookJson) . "\n", FILE_APPEND);
        $bot->sendMessage($statusChatId, "Informazioni raccolte!");
        setStatus($statusChatId, "start", 0);
    } */

    $db->close();
?>