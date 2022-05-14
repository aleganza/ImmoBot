<?php

    $string = 0;
    
    if($string != "0"){
        $string = intval($string);

    }else{
        $string = 0;
        echo("il numero è 0 ");
    }

    $int = intval($string);

    echo($int);
?>