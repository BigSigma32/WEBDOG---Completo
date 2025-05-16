<?php
session_start();
require("db_connect.php");

$userid = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : 0;
if ($userid > 0) {
    // Eliminazione account
    $sql = "DELETE FROM Utenti WHERE IDUtente = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $userid);
    if (mysqli_stmt_execute($stmt)) {
        echo "Account eliminato con successo.";

        // Elimina i cookie impostando la loro scadenza nel passato
        if (isset($_COOKIE)) {
            foreach ($_COOKIE as $name => $value) {
                setcookie($name, '', time() - 3600, '/');
                setcookie($name, '', time() - 3600, '/', '', false, true);
            }
        }

        session_destroy(); // Logout dell'utente
        header("Location: ../Home.php"); // Reindirizza alla home
        exit();
    } else {
        echo "Errore durante l'eliminazione dell'account.";
    }
} else {
    echo "Utente non autenticato.";
}
?>
