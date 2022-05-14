<?php
    $text = $jH->getText($webhookJson);
    $db = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);

    if($step == 0){
        // controllo che il codice fiscale esista
        $sql = "SELECT *
                FROM immobiliare_proprietari
                WHERE CF = '$text'";
        $rs = $db->query($sql);
        
        // cf esiste
        if($rs->num_rows != 0){
            // prelevo le informazioni da stampare
            $sql = "SELECT immo.*, inte.versamento
                    FROM immobiliare_intestazioni AS inte, immobiliare_immobili AS immo
                    WHERE inte.IdProp = '$text'
                    AND immo.Id = inte.IdImmob";
            $rs = $db->query($sql);
            $record = $rs->fetch_assoc();

            // invio mess con immobili

            $msg = "ℹ️ Immobili di ".$text.": ".PHP_EOL.PHP_EOL;

            while($record){
                $msg = $msg.
                       "🌐 Nome: ".$record["nome"].PHP_EOL.
                       "🚏 Via: ".$record["via"].", ".$record["civico"].PHP_EOL.
                       "📐 Metratura: ".$record["metratura"]."mq".PHP_EOL.
                       "🚪 Piano: ".$record["piano"]."°".PHP_EOL.
                       "🛏 Numero locali: ".$record["nLocali"].PHP_EOL.
                       "💸 Costo: ".$record["versamento"]."€".PHP_EOL.PHP_EOL
                ;

                $record = $rs->fetch_assoc();
            }

            $bot->sendMessage($statusChatId, $msg);
            setStatus($statusChatId, "start", 0);

        }else{
            $bot->sendMessage($statusChatId, "❌ Codice fiscale non presente".PHP_EOL."👇 Reinserisci il codice fiscale");
        }
    }
?>