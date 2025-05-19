<?php
session_start();
$ruolo = "";

// Controlla se il cookie esiste e imposta il ruolo
if (isset($_COOKIE['role'])) {
    $ruolo = $_COOKIE['role'];
} elseif (isset($_SESSION['role'])) {
    $ruolo = $_SESSION['role'];
}

// Se non è loggato, considera l'utente come ospite
if (!isset($_SESSION['user_id']) && !isset($_COOKIE['user_id'])) {
    $ruolo = "Guest";
}
?>


<html>
    <head>
        <title>WEBDOG HOME</title>
        <link rel="stylesheet" href="estetica.css">
    </head>
    <body bgcolor="#c1f3f6"></body>
</html>

<?php
    $id = $_GET['id'];
    if(!isset($_COOKIE['role']) || $_COOKIE['role'] == "Guest") {
        echo "<script type='text/javascript'>alert('&Egrave necessario eseguire il login prima di poter adottare.')</script>";
        header("Location: Galleria.php");
    } else {
        header("Location: DB\effettuaAdozione.php?id=$id");
    }
?>