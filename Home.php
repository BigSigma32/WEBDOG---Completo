<?php
session_start();
$ruolo = "";

if (isset($_COOKIE['role'])) {
    $ruolo = $_COOKIE['role'];
} elseif (isset($_SESSION['role'])) {
    $ruolo = $_SESSION['role'];
}

if (!isset($_SESSION['user_id']) && !isset($_COOKIE['user_id'])) {
    $ruolo = "Guest";
}
?>


<html>
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
    <div >
        <center>
        <img src="img/Home/fotohome.png" class="banner">
    </center>
    </div>
    <div class="home">

        <h2 class="scritta">Vuoi un amico a 4 zampe e abiti lontano?</h2>
        <h3 class="scritta">Non preoccuparti webdog è qui ad aiutarti. Puoi adottare un cane a distanza facendo delle donazioni a un amico a 4 zampe nel caso in cui tu non abbia tempo di occuparti di lui, noi ti manderemo foto e video di lui. </h3>
    <center><a href="Galleria.php"><img src="img/Home/home1.png" class="Dog"  alt="Foto profilo"></a></center>
    </div>
    <div class="home">

        <h2 class="scritta">Chi siamo?</h2>
        <h3 class="scritta">Siamo un azienda di volontari che amano gli animali, speriamo di trovare una casa accogliente per tutti i trovatelli presenti in canile e nel mentre noi di webdog ci prenderemo cura di loro</h3>
        <center><a href="Chisiamo.php"><img src="img/Home/WEBDOG (1).png" class="Dog" alt="Foto profilo"></a></center>
    </div> 
    <div class="home">
        <h2 class="scritta">Eventi</h2>
        <h3 class="scritta">Vieni agli eventi free entry adatti a tutti grandi e piccini. vai nell'aposità sezione per osservare i prossimi eventi e dove saranno tenuti, inoltre sarà possibile adottare un amico peloso.</h3>
        <center><a href="Eventi.php"><img src="img/Home/Dog3.jpg" class="Dog"  alt="Foto profilo"></a></center>
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
                    <li><a href="Donazioni.php" class="footlink">Donazioni</a></li>
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
