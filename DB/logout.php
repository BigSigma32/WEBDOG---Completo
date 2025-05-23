<?php
session_start();

// Svuota tutte le variabili di sessione
$_SESSION = array();

// Cancella il cookie di sessione (se esiste)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], 
        $params["domain"], 
        $params["secure"], 
        $params["httponly"]
    );
}

// Distruggi la sessione
session_destroy();

// Elimina anche i tuoi cookie personalizzati
setcookie("user_id", "", time() - 3600, "/");
setcookie("role", "", time() - 3600, "/");

// Redirect alla login
header("Location: ../Login1.php");
exit();
?>
