<?php
    require("db_config.php");

    $con = mysqli_connect($db_hostname, $db_username, $db_password, $db_name);

    if(!$con) {
        die("Impossibile connettersi al database");
    }
?>