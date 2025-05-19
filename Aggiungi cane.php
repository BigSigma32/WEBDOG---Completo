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
    // Gestione del form per l'aggiunta di un cane
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Ottieni i dati dal form
        $nome = $_POST['nome'];
        $descrizione = $_POST['descrizione'];
        $età = $_POST['età'];
        $sede = $_POST['sede'];
        $vaccinato = $_POST['vaccinato'];
        $microchip = $_POST['microchip'];

        // Gestione dell'upload dell'immagine
        if (isset($_FILES['foto_cane']) && $_FILES['foto_cane']['error'] == 0) {
            $imgTmpName = $_FILES['foto_cane']['tmp_name'];
            $imgName = $_FILES['foto_cane']['name'];
            $imgExtension = pathinfo($imgName, PATHINFO_EXTENSION);

            // Verifica il tipo di file (accettiamo solo immagini)
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            if (in_array(strtolower($imgExtension), $allowedExtensions)) {
                
                $imgNewName = uniqid('cane_', true) . '.' . $imgExtension;
                $imgUploadPath = 'img/Cani/' . $imgNewName;

                if (move_uploaded_file($imgTmpName, $imgUploadPath)) {
                    // Inserisci i dati nel database
                    $sql = "INSERT INTO Cani (Nome, Descrizione, Eta, IDfSede, Img1, Vaccinato, Microchip) VALUES (?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $con->prepare($sql);
                    $stmt->bind_param("sssisis", $nome, $descrizione, $età, $sede, $imgNewName, $vaccinato, $microchip);

                    if ($stmt->execute()) {
                        echo "<script>alert('Cane aggiunto con successo!');</script>";
                    } else {
                        echo "<script>alert('Errore durante l\'inserimento dei dati nel database.');</script>";
                    }
                } else {
                    echo "<script>alert('Errore nel caricamento dell\'immagine.');</script>";
                }
            } else {
                echo "<script>alert('Formato immagine non supportato.');</script>";
            }
        } else {
            echo "<script>alert('Si è verificato un errore nell\'upload dell\'immagine.');</script>";
        }
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
            } elseif($ruolo != 'User' && $ruolo != 'Admin') {
                echo "<a href='Login1.php'><img src='img/Login/user.jpg' alt='Foto profilo'></a>";
            } ?>
        </div>
    </div>

    <br><br><br>

    <center>
        <div class="wrapper">
            <form action="" method="post" enctype="multipart/form-data">
                <h1>Aggiungi cane</h1>
                <div class="input-box">
                    <input type="text" placeholder="Nome cane" name="nome" required>
                    <i class="bx bxs-user"></i>
                </div>
                <div class="input-box">
                    <input type="text" placeholder="Descrizione" name="descrizione" required>
                    <i class="bx bxs-lock-alt"></i>
                </div>
                <div class="input-box">
                    <input type="text" placeholder="Età (es. 18 mesi, 5 anni etc)" name="età" required>
                    <i class="bx bxs-lock-alt"></i>
                </div>
                    Indica se il cane è vaccinato:<br>
                    Si: <input type="radio" name="vaccinato" value="1" required>
                    No: <input type="radio" name="vaccinato" value="0" required><br><br>
                    Indica se il cane è provvisto di microchip:<br>
                    Si: <input type="radio" name="microchip" value="1" required>
                    No: <input type="radio" name="microchip" value="0" required><br>

                    <i class="bx bxs-lock-alt"></i>
                <div class="input-box">
                    <select name="sede" required>
                       <option value="" disabled selected>Seleziona sede dove risiede il cane</option>
                       <?php 
                            $sql = "SELECT * FROM Sedi";
                            $sedi = mysqli_query($con,$sql);
                            foreach($sedi as $i => $row)
                                {
                                    echo "<option value='" . $row['IDSede'] . "'>"; echo $row['Città']; echo "</option>";
                                }
                        ?>
                    </select>
                </div>
                <div class="input-box">
                    <input type="file" name="foto_cane" accept="image/*" required>
                </div>
                <button type="submit" class="btn">Aggiungi Cane</button>
            </form>
        </div>
    </center>

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
