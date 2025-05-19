
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
?><html>
    <head>
        <title>WEBDOG HOME</title>
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
            <a href="Login1.php"><img src="img/Login/user.jpg" alt="Foto profilo"></a>
        </div>
        </div>  
        <br>
        <br>
        <br>
            
            
        </div>

        <center>
        
        <div class="wrapper">
            <form action="DB/Dona.php" method="post">
                <h1>Fai una donazione</h1>
                <div class="input-box">
                    <input type="text" placeholder="Titolare della carta" name="titolare" required>
                    <i class="bx bxs-user"></i>
                </div>
                <div class="input-box">
                    <input type="text" placeholder="XXXX-XXXX-XXXX-XXXX" name="numcarta" required>
                    <i class="bx bxs-user"></i>
                </div>
                <div class="input-box">
                    <input type="text" placeholder="mm/aa" name="datascadenza" required>
                    <i class="bx bxs-lock-alt"></i>
                </div>
                <div class="input-box">
                    <input type="text" placeholder="***" name="codicesicurezza" required>
                    <i class="bx bxs-lock-alt"></i>
                </div>
                <div class="remember-forgot">
                    <input type="radio" name="tipocarta" value="VISA">VISA<br>
                    <input type="radio" name="tipocarta" value="MasterCard">MasterCard<br>
                </div>
                <div class="input-box">
                    <input type="text" placeholder="Messaggio personalizzato" name="messaggio">
                    <i class="bx bxs-lock-alt"></i>
                </div>
                <div class="input-box">
                    <input type="text" placeholder="Importo (EUR)" name="importo">
                    <i class="bx bxs-lock-alt"></i>
                </div>
                <button type="submit" class="btn">Effettua pagamento</button>
            </form>
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
                    <li><a href="Galleria.html" class="footlink">Adotta un Amico</a></li>
                    <li><a href="Chisiamo.html" class="footlink">Chi Siamo</a></li>
                    <li><a href="#" class="footlink">Donazioni</a></li>
                    <li><a href="Contatti.html" class="footlink">Contatti</a></li>
                </ul>
            </div>
            <div class="footposition">
            <h4>Social</h4>
            <h5>Instagram: <a href="https://www.instagram.com/_marchitos__/" class="footlink">WEBDOG_Ita</a></li></h5>
            <h5>FaceBook: <a href="https://www.facebook.com/profile.php?id=61568809316208"class="footlink">Webdog_love</a></li></h5>
            </div>
            <div class="footposition">
            <h4>Orari invernali</h4>
            <h5>Lunedì-Venerdì: 8/12 14/18</h5>
            <h4>Orari estivi</h4>
            <h5>Lunedì-Venerdì: 7/12 14/19</h5>
            </div>
            <div >
                <center><h8 class="h8">Copyright @ 2025 WEBDOG ASSOCIAZIONE AMICI DEGLI ANIMALI ITALIA</h8></center>
            </div>
        </div>
    </body>
</html>
