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
            
            $sqlProp = "SELECT prop.*
                        FROM immobiliare_proprietari AS prop
                        WHERE CF = '$text'";
            $rsProp = $db->query($sqlProp);
            $recordProp = $rsProp->fetch_assoc();

            $sqlImmo = "SELECT immo.*
                        FROM immobiliare_immobili AS immo, immobiliare_intestazioni AS inte
                        WHERE inte.IdProp = '$text'
                        AND immo.Id = inte.IdImmob";
            $rsImmo = $db->query($sqlImmo);
            $recordImmo = $rsImmo->fetch_assoc();

            $msg = "📊 Dati di ". $recordProp["nome"] . " " . $recordProp["cognome"] . " (" . $text . "): ".PHP_EOL.PHP_EOL.
                   "📱 Telefono: " . $recordProp["telefono"].PHP_EOL.
                   "📧 Email: " . $recordProp["email"].PHP_EOL.PHP_EOL.
                   "🏢 Immobili: ".PHP_EOL
            ;

            while($recordImmo){
                $msg = $msg . " - " . $recordImmo["nome"].PHP_EOL;

                $recordImmo = $rsImmo->fetch_assoc();
            }

            $bot->sendMessage($statusChatId, $msg);
            setStatus($statusChatId, "start", 0);
        }else{
            $bot->sendMessage($statusChatId, "❌ Codice fiscale non presente".PHP_EOL."👇 Reinserisci il codice fiscale");
        }
    }
?>