<?php
    session_start();
    require("DB/db_connect.php");

    // Verifica se l'utente è loggato tramite cookie o sessione
    $userid = isset($_COOKIE['user_id']) ? (int) $_COOKIE['user_id'] : (isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : 0);
    $nome = "Ospite";
    $ruolo = "Guest"; 

    // Se l'utente è loggato
    if ($userid > 0) {
        // Query per ottenere il nome e il ruolo dell'utente dal database
        $sql = "SELECT Nome, Ruolo FROM Utenti WHERE IDUtente = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "i", $userid);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        // Se l'utente esiste, imposta il nome e il ruolo
        if ($row = mysqli_fetch_assoc($result)) {
            $nome = htmlspecialchars($row['Nome']); // Protezione XSS
            $ruolo = $row['Ruolo']; // Imposta il ruolo dell'utente
        } else {
            $ruolo = "Guest"; // Se l'utente non esiste, imposta il ruolo come guest
        }
    }

    if($ruolo == "Guest") {
        header("Location: Login1.php");
    }
?>

<html>
<head>
    <link rel="stylesheet" href="estetica.css">
</head>

<body bgcolor="#c1f3f6">
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
            <?php if ($ruolo == 'Admin' || $ruolo == 'User') { ?>
                <a href='AreaPersonale.php'><img src='img/Login/user.jpg' alt='Foto profilo'></a>
            <?php } else { ?>
                <a href='Login1.php'><img src='img/Login/user.jpg' alt='Foto profilo'></a>
            <?php } ?>
        </div>
    </div>

    <br><br><br>

    <center>
        <img src="img/Login/user.jpg" alt="Foto profilo" width="5%" style="border-radius: 90%;">
        <h1 style="font-family:cursive">Bentornato/a <?php echo $nome; ?></h1>

        <div class="wrapper">
            <button class="input-box" onclick="location.href='DB/modificaNome.php'">Modifica nome</button>
            <button class="input-box" onclick="location.href='DB/modificaEmail.php'">Modifica indirizzo email</button>
            <button class="input-box" onclick="location.href='DB/modificaPassword.php'">Modifica password</button>
            <button class="input-box" onclick="location.href='DB/eliminaAccount.php'">Elimina account</button>
            <button class="input-box" onclick="location.href='DB/logout.php'">Logout</button>
        </div>
        
        <?php
        // Mostra messaggio in base al ruolo dell'utente
        if ($ruolo == "User") {
            echo "<p>Benvenuto nella tua area personale!</p>";
        } else if ($ruolo == "Admin") {
            echo "<p>Accesso admin disponibile. Puoi gestire gli utenti.</p>";
        } else {
            echo "<p>Errore: utente non riconosciuto.</p>";
        }
        ?>
    </center>
</body>
</html>
