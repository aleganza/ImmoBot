<?php
    $text = $jH->getText($webhookJson);
    $db = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);

    if($step == 0){
        
        $sql = "INSERT INTO immobot_proprietari(tempChatId)
                VALUES ($statusChatId)";
        $rs = $db->query($sql);

        $sql = "SELECT *
                FROM immobot_proprietari
                WHERE CF = '$text'";
        $rs = $db->query($sql);

        if($rs->num_rows == 0){
            $sql = "INSERT INTO immobot_proprietari(CF)
                    VALUES ('$text')
                    WHERE tempChatId = $statusChatId";
            $rs = $db->query($sql);

            $bot->sendMessage($statusChatId, "Inserisci nome");
            setStatus($statusChatId, "registrati", 1);

        }else{
            // chiudo il processo di registrazione
            $bot->sendMessage($statusChatId, "Il codice fiscale è già in uso");
            setStatus($statusChatId, "start", 0);

            // elimino la riga che si stava creando per il nuovo utente registrato
            $sql = "DELETE FROM immobot_proprietari
                    WHERE tempChatId = '$statusChatId'";
        }
    }
    if($step == 1){
        
        $sql = "INSERT INTO immobot_proprietari(nome)
                VALUES ('$text')
                WHERE tempChatId = $statusChatId";
        $rs = $db->query($sql);

        $bot->sendMessage($statusChatId, "Inserisci cognome");
        setStatus($statusChatId, "registrati", 2);
    }
    if($step == 2){
        
        $sql = "INSERT INTO immobot_proprietari(cognome)
                VALUES ('$text')
                WHERE tempChatId = $statusChatId";
        $rs = $db->query($sql);

        $bot->sendMessage($statusChatId, "Inserisci telefono");
        setStatus($statusChatId, "registrati", 3);
    }
    if($step == 3){
        
        $sql = "INSERT INTO immobot_proprietari(telefono)
                VALUES ($text)
                WHERE tempChatId = $statusChatId";
        $rs = $db->query($sql);

        $bot->sendMessage($statusChatId, "Inserisci email");
        setStatus($statusChatId, "registrati", 4);
    }
    if($step == 4){
        
        $sql = "INSERT INTO immobot_proprietari(email)
                VALUES ('$text')
                WHERE tempChatId = $statusChatId";
        $rs = $db->query($sql);

        $bot->sendMessage($statusChatId, "Inserisci password");
        setStatus($statusChatId, "registrati", 1);
    }
    if($step == 5){
        
        $sql = "INSERT INTO immobot_proprietari(password)
                VALUES ('$text')
                WHERE tempChatId = $statusChatId";
        $rs = $db->query($sql);

        $bot->sendMessage($statusChatId, "Informazioni raccolte!" . PHP_EOL . "Ora puoi loggarti");
        setStatus($statusChatId, "start", 0);
    }

    $db->close();
?>