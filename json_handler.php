<?php
    $json = file_get_contents('php://input');

    /* file_put_contents("data.log", $json, FILE_APPEND); */

    $jsonDecoded = json_decode($json, true);
?>