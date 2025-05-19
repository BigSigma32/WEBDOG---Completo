<?php
require("DB/db_connect.php");
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
$con = mysqli_connect($db_hostname, $db_username, $db_password, $db_name);
if (!$con) {
    die("Connessione al database fallita: " . mysqli_connect_error());
}
$sqlIncarichi = "SELECT * FROM Incarichi";
$resultIncarichi = mysqli_query($con, $sqlIncarichi);

if ($ruolo != "Admin") {header('Location: Home.php');}
?>
<html>
    <head>
        <title>WEBDOG HOME</title>
        <link rel="stylesheet" href="estetica.css">
    </head>
<body class="sfondo">
    <div class="navbar">
        <div class="logo-container">
            <img src="img/Home/WEBDOG (1).png" alt="Logo">
            <h1 class="title">WEBDOG</h1>
        </div>
    
        <div class="nav-links">
            <a href="Home.php">Home</a>
            <a href="Galleria.php">Galleria</a>
            <a href="Chisiamo.php">Chi siamo?</a>
            <a href="Eventi.php">Eventi</a>
            <a href="Donazioni.php">Donazioni</a>
            <?php if ($ruolo == 'Admin') { ?>
                <a href="Gestione.php">Gestione Utenti</a>
                <a href="GestioneAddetti.php">Gestione Addetti</a>
                <a href="DB\GestioneCani.php">Gestione Cani</a>
                <a href="GestioneAdozioni.php">Gestione Adozioni</a>
            <?php } ?>
        </div>
    
        <div class="login">
        <?php if ($ruolo == 'Admin' || $ruolo == 'User') {
            echo "<a href='Area Personale.php'><img src='img/Login/user.jpg' alt='Foto profilo'></a>";
            } 
        
            elseif($ruolo != 'User' && $ruolo != 'Admin') {
            echo "<a href='Login1.php'><img src='img/Login/user.jpg' alt='Foto profilo'></a>";
             }?>
        </div>
    </div>   
    <br>
    <br>
    <br>
    <center>
    <div class="wrapper">
        <form action="DB/AggRuolo.php" method="post">
            <h1>Crea Nuovo Ruolo</h1>
            <div class="input-box via-civico">
                <input type="text" class="via" name="nomeRuolo" placeholder="Nome Ruolo" required>
            </div>
            <div class="input-box via-civico">
                <input type="text" class="via" name="descrizioneRuolo" placeholder="Descrizione Ruolo" required>
            </div>
            <button type="submit" class="btn">Crea Ruolo</button>
        </form>
    </div>
    </center>
</body>
</html>
