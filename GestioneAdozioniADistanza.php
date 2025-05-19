<?php
session_start();
$ruolo = "";

// Controlla se il cookie esiste e imposta il ruolo
if (isset($_COOKIE['Ruolo'])) {
    $ruolo = $_COOKIE['ruolo'];
} elseif (isset($_SESSION['ruolo'])) {
    $ruolo = $_SESSION['ruolo'];
}

// Se non è loggato, considera l'utente come ospite
if (!isset($_SESSION['user_id']) && !isset($_COOKIE['user_id'])) {
    $ruolo = "User";
}
?>

<html>
<head>
    <title>Gestione Utenti - WEBDOG</title>
    <!-- Collega il file CSS per gli stili generali e per la tabella -->
    <link rel="stylesheet" href="estetica.css"> <!-- Assicurati che il percorso sia corretto -->
    <style>
        /* CSS direttamente nel PHP per le tabelle */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #ffffff;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .table th {
            background-color: #0871a1;
            color: #fff;
            padding: 12px 15px;
            font-size: 18px;
            text-align: center;
        }

        .table td {
            padding: 12px 15px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .table tr:nth-child(odd) {
            background-color: #ffffff;
        }

        .table tr:hover {
            background-color: #f1f1f1;
        }

        .table input[type="submit"], .table button {
            padding: 8px 12px;
            font-size: 14px;
            background-color: #008CBA;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .table input[type="submit"]:hover, .table button:hover {
            background-color: #005f7f;
        }

        .table form {
            display: inline-block;
            margin: 0 5px;
        }

        .table form button {
            padding: 6px 12px;
            background-color: #e74c3c;
            border-radius: 5px;
            color: white;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .table form button:hover {
            background-color: #c0392b;
        }
    </style>
</head>

<body>
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
            <?php 
            if ($ruolo == 'Admin' || $ruolo == 'User') {
                echo "<a href='Area Personale.php'><img src='img/Login/user.jpg' alt='Foto profilo' class='fotoprofilo'></a>";
            } elseif($ruolo != 'User' && $ruolo != 'Admin') {
                echo "<a href='Login1.php'><img src='img/Login/user.jpg' alt='Foto profilo' class='fotoprofilo'></a>";
            } ?>
        </div>
    </div>

    <br><br><br>

    <?php
    require("DB/db_connect.php");
    $con = mysqli_connect($db_hostname, $db_username, $db_password, $db_name);

    if (!$con) {
        die("Connessione al database fallita: " . mysqli_connect_error());
    }

    // Recupera gli utenti dal database
    $sql = "SELECT *, c.Nome AS nomeCane, u.Nome AS nomeUtente FROM AdozioniADistanza a
            INNER JOIN Utenti u ON u.IDUtente = a.IDfUtente
            INNER JOIN Cani c ON c.IDCane = a.IDfCane
            ORDER BY DataAdozione";
    $result = mysqli_query($con, $sql);

    if (!$result) {
        die("Errore nella query: " . mysqli_error($con));
    }

    echo "<table class='table'>";
    echo "<tr>
            <th>ID Adozione</th>
            <th>Utente</th>
            <th>Cane</th>
            <th>Data adozione</th>
            <th>Termine adozione</th>
            <th>Azioni</th>
          </tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['IDAdozioneDistanza']) . "</td>";
        echo "<td>" . htmlspecialchars($row['nomeUtente']) . "</td>";
        echo "<td>" . htmlspecialchars($row['nomeCane']) . "</td>";
        echo "<td>" . htmlspecialchars($row['DataAdozione']) . "</td>";
        if($row['TermineAdozione'] == NULL) {
            echo "<td>Ancora in corso</td>";
        } else {
            echo "<td>" . htmlspecialchars($row['TermineAdozione']) . "</td>";
        }
        
        echo "<td>
                <form action='' method='post' style='display:inline;'>
                    <input type='hidden' name='id' value='" . htmlspecialchars($row['IDAdozioneDistanza']) . "'>
                    <input type='submit' name='edit' value='Modifica'>
                </form>
                
                <form action='DB/Elimina riga.php' method='post' style='display:inline;'>
                    <input type='hidden' name='id' value='" . htmlspecialchars($row['IDAdozioneDistanza']) . "'>
                    <button type='submit' onclick='return confirm(\"Sei sicuro di voler eliminare questo utente?\");'>Elimina</button>
                </form>
              </td>";
        echo "</tr>";
    }

    echo "</table>";

    if (isset($_POST['edit'])) {
        $id = $_POST['id'];

        $sql = "SELECT * FROM AdozioniADistanza WHERE IDAdozioneDistanza = $id";

        $data = mysqli_query($con, $sql);

        $sql = "SELECT * FROM Utenti ORDER BY Cognome";
        $dataUser = mysqli_query($con, $sql);
        $sql = "SELECT * FROM Cani ORDER BY Nome";
        $dataCani = mysqli_query($con, $sql);

        echo "<form action='' method='post'>
                <input type='hidden' name='id' value='" . htmlspecialchars($id) . "'>
                <label for='utente'>Utente:</label>
                <select name='utente' id='ruolo'>";
                foreach($dataUser as $i => $row) {
                    echo "<option value='"; echo $row["IDUtente"]; echo "'>"; echo $row["Cognome"]; echo " "; echo $row["Nome"]; echo "</option>";
                }
        echo "</select>";
        echo "<select name='cane' id='ruolo'>";
                foreach($dataCani as $i => $row) {
                    echo "<option value='"; echo $row["IDCane"]; echo "'>"; echo $row["Nome"]; echo "</option>";
                }
        echo "</select>";
                foreach($data as $i => $row) {
                    echo "<input type='date' name='dataadozione' value='"; echo $row["DataAdozione"]; echo "'>";
                    echo "<input type='date' name='termineadozione' value='"; echo $row["TermineAdozione"]; echo "'>";
                }
        echo "
                <button type='submit' name='update'>Aggiorna</button>
              </form>";
    }

    // Se viene inviato il form per aggiornare il ruolo
    if (isset($_POST['update'])) {
        $id = $_POST['id'];
        $idUtente = $_POST["utente"];
        $idCane = $_POST["cane"];
        $dataAd = $_POST["dataadozione"];
        $termineAd = $_POST["termineadozione"];

        $sql = "UPDATE AdozioniADistanza SET IDfUtente = $idUtente, IDfCane = $idCane, DataAdozione = '$dataAd', TermineAdozione = '$termineAd' WHERE IDAdozioneDistanza = $id";

        if(!mysqli_query($con, $sql)) {
            die("Errore nell'aggirnamento.");
        }
    }
    ?>

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
