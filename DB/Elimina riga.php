<?php
require("db_connect.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {
    $id = intval($_POST["id"]); // Converte in intero per sicurezza

    // Usa un prepared statement per evitare SQL Injection
    $sql = "DELETE FROM Utenti WHERE IDUtente = ?";
    $stmt = mysqli_prepare($con, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);

        // Controllo se l'eliminazione è avvenuta con successo
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            header("location: ../Gestione.php");
        } else {
            echo "Errore: ID non trovato.";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Errore nella query.";
    }
} else {
    echo "ID non valido.";
}

mysqli_close($con);
?>