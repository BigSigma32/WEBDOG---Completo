<?php
session_start();
require("db_connect.php");

$ruolo = "";

// Controlla se l'utente è loggato tramite cookie o sessione
if (isset($_COOKIE['role'])) {
    $ruolo = $_COOKIE['role'];
} elseif (isset($_SESSION['role'])) {
    $ruolo = $_SESSION['role'];
}

// Se non è loggato, reindirizza al login
if (!isset($_SESSION['user_id']) && !isset($_COOKIE['user_id'])) {
    header("Location: ../Login1.php");
    exit();
}

$userid = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : (isset($_COOKIE['user_id']) ? (int) $_COOKIE['user_id'] : 0);

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($userid > 0) {
        $newPassword = mysqli_real_escape_string($con, $_POST['password']);
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT); 
        $sql = "UPDATE Utenti SET Password = ? WHERE IDUtente = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "si", $hashedPassword, $userid);
        if (mysqli_stmt_execute($stmt)) {
            $message = "<div class='success-message'>Password modificata con successo!</div>";
        } else {
            $message = "<div class='error-message'>Errore durante la modifica della password.</div>";
        }
    } else {
        $message = "<div class='error-message'>Utente non autenticato.</div>";
    }
}
?>

<html>
<head>
    <title>Modifica Password - WEBDOG</title>
    <link rel="stylesheet" href="../estetica.css">
</head>

<body bgcolor="#c1f3f6">
    <div class="navbar">
        <div class="logo-container">
            <img src="../img/Home/WEBDOG (1).png" alt="Logo">
            <h1 class="title">WEBDOG</h1>
        </div>

        <div class="nav-links">
            <a href="../Home.php">Home</a>
            <a href="../Galleria.php">Galleria</a>
            <a href="../Chisiamo.php">Chi siamo?</a>
            <a href="../Eventi.php">Eventi</a>
            <a href="../Donazioni.php">Donazioni</a>
            <?php if ($ruolo == 'Admin') { ?>
                <a href="../Gestione.php">Gestione Utenti</a>
                <a href="../GestioneAddetti.php">Gestione Addetti</a>
            <?php } ?>
        </div>

        <div class="login">
            <?php 
            if ($ruolo == 'Admin' || $ruolo == 'User') {
                echo "<a href='../Area Personale.php'><img src='../img/Login/user.jpg' alt='Foto profilo'></a>";
            } else {
                echo "<a href='../Login1.php'><img src='../img/Login/user.jpg' alt='Foto profilo'></a>";
            }
            ?>
        </div>
    </div>

    <br><br><br>

    <div>
        <center>
        <div class="wrapper">
            <h1>Modifica Password</h1>
            <?php echo $message; ?>
            <form method="POST" action="modificaPassword.php">
                <label for="password">Nuova password:</label>
                <input type="password" id="password" name="password" required>
                <button type="submit" class="btn">Modifica Password</button>
            </form>
        </div>
        </center>
    </div>

    <div id="push"></div>
    <div id="container">
        <div id="footer" class="footcolor">
            <h3 class="foottitle">Canile WEBDOG</h3>
            <div class="footposition">
                <h3>Informazioni</h3>
                <p>Via della Zampa, 123 - 00100 Città Felice</p>
                <p>Email: info@amicifedeli.com</p>
                <p>Telefono: +39 354 752 1879</p>
            </div>
            <div class="footposition">
                <h4>Link Utili</h4>
                <ul>
                    <li><a href="../Galleria.html" class="footlink">Adotta un Amico</a></li>
                    <li><a href="../Chisiamo.html" class="footlink">Chi Siamo</a></li>
                    <li><a href="#" class="footlink">Donazioni</a></li>
                    <li><a href="../Contatti.html" class="footlink">Contatti</a></li>
                </ul>
            </div>
            <div class="footposition">
                <h4>Social</h4>
                <h5>Instagram: <a href="https://www.instagram.com/_marchitos__/" class="footlink">WEBDOG_Ita</a></h5>
                <h5>FaceBook: <a href="https://www.facebook.com/profile.php?id=61568809316208" class="footlink">Webdog_love</a></h5>
            </div>
            <div class="footposition">
                <h4>Orari invernali</h4>
                <h5>Lunedì-Venerdì: 8/12 14/18</h5>
                <h4>Orari estivi</h4>
                <h5>Lunedì-Venerdì: 7/12 14/19</h5>
            </div>
            <div>
                <center><h8 class="h8">Copyright @ 2025 WEBDOG ASSOCIAZIONE AMICI DEGLI ANIMALI ITALIA</h8></center>
            </div>
        </div>
    </div>
</body>
</html>
