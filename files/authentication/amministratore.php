<?php
    $text = $jH->getText($webhookJson);

    if($step == 0){
        if($text == USERNAME){
            $bot->sendMessage($statusChatId, "Inserisci password");
            setStatus($statusChatId, "amministratore", 1);
        }else{
            $bot->sendMessage($statusChatId, "❌ Username non corretto");
        }
    }
    if($step == 1){
        if($text == PASSWORD){
            $bot->sendMessage($statusChatId, "✅ Login come amministratore avvenuto!");
            setLogged($statusChatId, 2);
            $bot->sendMessage($statusChatId, "➡️ Ora puoi eseguire /funzioni");
        }else{
            $bot->sendMessage($statusChatId, "❌ Password non corretta");
        }
    }

?>