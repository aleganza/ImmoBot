<?php

    /* registrazione
     * ogni informazione richiesta all'utente è rappresentato da uno step
     * ogni informazione inviata dall'utente viene registrata in database e successivamente viene aumentato lo step
     */

    $text = $jH->getText($webhookJson);
    $db = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);

    if($step == 0){
        
        /* file_put_contents("data.log", "entro" . "\n", FILE_APPEND); */

        // inserisco chat id nella tabella, se esiste già faccio a meno (vorrebbe dire che è già aperte una sessione di registrazione)
        $sql = "SELECT *
                FROM immobiliare_proprietari
                WHERE tempChatId = $statusChatId";
        $rs = $db->query($sql);
        if($rs->num_rows == 0){
            $sql = "INSERT INTO immobiliare_proprietari(nome, cognome, telefono, email, password, tempChatId)
                VALUES (null, null, null, null, null, $statusChatId)";
            $rs = $db->query($sql);
        }

        // inserisco il cf nella tabella, se ne esiste già uno uguale lo comunico e non inserisco
        $sql = "SELECT *
                FROM immobiliare_proprietari
                WHERE CF = '$text'";
        $rs = $db->query($sql);

        if($rs->num_rows == 0){

            $sql = "UPDATE immobiliare_proprietari
                    SET CF = '$text'
                    WHERE tempChatId = $statusChatId";
            $rs = $db->query($sql);

            $bot->sendMessage($statusChatId, "Inserisci nome");
            setStatus($statusChatId, "registrati", 1);

        }else{
            // richiedo la credenziale
            $bot->sendMessage($statusChatId, "❌ Il codice fiscale inserito è già registrato");
            $bot->sendMessage($statusChatId, "Inserisci codice fiscale");

            // elimino la riga che si stava creando per il nuovo utente registrato
            $sql = "DELETE FROM immobiliare_proprietari
                    WHERE tempChatId = '$statusChatId'";
            $rs = $db->query($sql);
        }
    }
    if($step == 1){

        $sql = "UPDATE immobiliare_proprietari
                SET nome = '$text'
                WHERE tempChatId = $statusChatId";
        $rs = $db->query($sql);

        $bot->sendMessage($statusChatId, "Inserisci cognome");
        setStatus($statusChatId, "registrati", 2);
    }
    if($step == 2){
        
        $sql = "UPDATE immobiliare_proprietari
                SET cognome = '$text'
                WHERE tempChatId = $statusChatId";
        $rs = $db->query($sql);

        $bot->sendMessage($statusChatId, "Inserisci telefono");
        setStatus($statusChatId, "registrati", 3);
    }
    if($step == 3){
        // controllo che il numero di telefono vada bene
        

        $sql = "SELECT *
                FROM immobiliare_proprietari
                WHERE telefono = '$text'";
        $rs = $db->query($sql);

        if($rs->num_rows == 0){

            $sql = "UPDATE immobiliare_proprietari
                    SET telefono = '$text'
                    WHERE tempChatId = $statusChatId";
            $rs = $db->query($sql);

            $bot->sendMessage($statusChatId, "Inserisci email");
            setStatus($statusChatId, "registrati", 4);

        }else{
            // chiudo il processo di registrazione
            $bot->sendMessage($statusChatId, "❌ Il numero di telefono inserito è già registrato");
            $bot->sendMessage($statusChatId, "Inserisci telefono");

            // elimino la riga che si stava creando per il nuovo utente registrato (resetto il tutto)
            $sql = "DELETE FROM immobiliare_proprietari
                    WHERE tempChatId = '$statusChatId'";
            $rs = $db->query($sql);
        }
    }
    if($step == 4){

        $sql = "SELECT *
                FROM immobiliare_proprietari
                WHERE email = '$text'";
        $rs = $db->query($sql);

        if($rs->num_rows == 0){

            $sql = "UPDATE immobiliare_proprietari
                    SET email = '$text'
                    WHERE tempChatId = $statusChatId";
            $rs = $db->query($sql);

            $bot->sendMessage($statusChatId, "Inserisci password");
            setStatus($statusChatId, "registrati", 5);

        }else{
            // chiudo il processo di registrazione
            $bot->sendMessage($statusChatId, "❌ L'email inserita è già registrata");
            $bot->sendMessage($statusChatId, "Inserisci email");

            // elimino la riga che si stava creando per il nuovo utente registrato (resetto il tutto)
            $sql = "DELETE FROM immobiliare_proprietari
                    WHERE tempChatId = '$statusChatId'";
            $rs = $db->query($sql);
        }
    }
    if($step == 5){
        $text = md5($text); // codifico in hash  md5 la password
        $sql = "UPDATE immobiliare_proprietari
                SET password = '$text'
                WHERE tempChatId = $statusChatId";
        $rs = $db->query($sql);

        $bot->sendMessage($statusChatId, "✅ Utente registrato!" . PHP_EOL . "Ora puoi eseguire il Login");
        setStatus($statusChatId, "start", 0);

        // elimino tempChatId visto che non mi serve più
        $sql = "UPDATE immobiliare_proprietari
                SET tempChatId = 0
                WHERE tempChatId = $statusChatId";
        $rs = $db->query($sql);
    }

    $db->close();
?>