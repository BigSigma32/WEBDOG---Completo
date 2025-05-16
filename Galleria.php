<?php
    require("DB/db_connect.php");

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
    <!--
    <div>
        <a href="Eventi.html"><img src="img/Galleria/f1.jpg" class="galleria" width="350px" alt="Foto profilo"></a>
        <a href="Eventi.html"><img src="img/Galleria/f4.jpg" class="galleria" width="150px" alt="Foto profilo"></a>
        <a href="Eventi.html"><img src="img/Galleria/f5.jpeg" class="galleria" width="150px" alt="Foto profilo"></a>
        <a href="Eventi.html"><img src="img/Galleria/f7.jpg" class="galleria" width="150px" alt="Foto profilo"></a>
        <a href="Eventi.html"><img src="img/Galleria/f8.jpg" class="galleria" width="150px" alt="Foto profilo"></a>
        <a href="Eventi.html"><img src="img/Galleria/f9.jpg" class="galleria" width="150px" alt="Foto profilo"></a>
        <a href="Eventi.html"><img src="img/Galleria/f10.jpg" class="galleria" width="150px" alt="Foto profilo"></a>
        <a href="Eventi.html"><img src="img/Galleria/f11.jpg" class="galleria" width="150px" alt="Foto profilo"></a>
        <a href="Eventi.html"><img src="img/Galleria/f12.jpg" class="galleria" width="150px" alt="Foto profilo"></a>
        <a href="Eventi.html"><img src="img/Galleria/f13.jpeg" class="galleria" width="150px" alt="Foto profilo"></a>
    </div>
    -->
    <?php
        if($ruolo == 'Admin') {
            echo "<center><a href='Aggiungi cane.php'><button>Aggiungi cane</button></a></center><br>";
        }
    ?>

    <?php
        $sql = "SELECT * FROM Cani
            INNER JOIN Sedi ON Cani.IDfSede = Sedi.IDSede
            WHERE Stato = 0";

        $cani = mysqli_query($con, $sql);

        if(!$cani) {
            exit("Non ci sono cani disponibili");
        }

        $row = mysqli_fetch_assoc($cani);

        foreach($cani as $i => $row) {
            $id = $row['IDCane'];
            echo "<a href='Adotta.php?id=$id' id='link_adozione'>";
            echo "<div class='cani_galleria'>";
            echo "<h3>".$row["Nome"]."</h3>";
            echo "<img src='data:image/jpeg;base64,".base64_encode($row['Img1'])."' id='immagine_cane'/><br><br>";
            echo $row["Descrizione"]; echo "<br>";
            /*
            echo "Et&agrave: ".$row["Eta"]; echo "<br>";
            echo "Vaccinato: ";
            if($row["Vaccinato"] == TRUE)
                echo "si";
            else
                echo "no";
            echo "<br>";
            echo "Microchippato: ";
            if($row["Microchip"] == TRUE)
                echo "si";
            else
                echo "no";
            */
            /*
            echo "<a href='Adotta.php?id=$id'><button>Info</button></a>";
            */
            echo "</div>";
            echo "</a>";
        }
    ?>
    <div id="push"></div>
    <div id="container">
        <div id="footer" class="footcolor">
        <h3 class="foottitle">Canile WEBDOG</h3>
        <div class="footposition">
        <h3>Informazioni</h3>
        <p>Via della Zampa, 123 - 00100 Citta Felice</p>
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