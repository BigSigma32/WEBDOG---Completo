<?php
    require("db_connect.php");
    $nome = $_POST["nome"];
    $desc = $_POST["descrizione"];
    $età = $_POST["età"];
    $sede = $_POST["sede"];
    $foto = $_POST["foto_cane"];
    $vaccinato = $_POST["vaccinato"];
    $microchip = $_POST["microchip"];

    $sql = "INSERT INTO Cani (Nome, Descrizione, Età, Vaccinato, Microchip, Sede, Img1, Stato)
        VALUES ('$nome', '$desc', '$età', '$vaccinato', '$microchip', $sede, '$foto', 0)";

    if(!mysqli_query($con, $sql)) {
        die("Errore");
    }
?>