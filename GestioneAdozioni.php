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

//if ($ruolo != "Admin") {header('Location: Home.php');}
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
    $sql = "SELECT a.IDAdozione, u.Nome AS NomeUtente, u.Cognome, u.Email, u.NumeroTelefono, u.Provincia, u.Città, u.Via, u.NumeroCivico, u.CAP, u.Ruolo,
                   c.Nome AS NomeCane, c.Descrizione, c.Eta, c.Vaccinato, c.Microchip,
                   a.DataRichiesta, a.Stato, a.DataRitiro
            FROM Adozioni a
            INNER JOIN Utenti u ON u.IDUtente = a.IDfUtente
            INNER JOIN Cani c ON c.IDCane = a.IDfCane";
    $result = mysqli_query($con, $sql);

    if (!$result) {
        die("Errore nella query: " . mysqli_error($con));
    }

    echo "<table class='table' style='width: 60%; margin: auto;'>";
    echo "<tr>
            <th>Nome Utente</th>
            <th>Cognome</th>
            <th>Email</th>
            <th>Numero Telefono</th>
            <th>Città</th>
            <th>Nome Cane</th>
            <th>Vaccinato</th>
            <th>Microchip</th>
            <th>Data Richiesta</th>
            <th>Stato</th>
            <th>Data Ritiro</th>
            <th>Azioni</th>
          </tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        
        $vaccinato = $row['Vaccinato'] ? 'Sì' : 'No';
        $microchip = $row['Microchip'] ? 'Sì' : 'No';

       
        $stato = '';
        switch ($row['Stato']) {
            case 0:
                $stato = 'Richiesta in attesa';
                break;
            case 1:
                $stato = 'Approvata';
                break;
            case 2:
                $stato = 'Rifiutata';
                break;
            default:
                $stato = 'Sconosciuto';
        }

        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['NomeUtente']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Cognome']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Email']) . "</td>";
        echo "<td>" . htmlspecialchars($row['NumeroTelefono']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Città']) . "</td>";
        echo "<td>" . htmlspecialchars($row['NomeCane']) . "</td>";
        echo "<td>" . htmlspecialchars($vaccinato) . "</td>";
        echo "<td>" . htmlspecialchars($microchip) . "</td>";
        echo "<td>" . htmlspecialchars($row['DataRichiesta']) . "</td>";
        echo "<td>" . htmlspecialchars($stato) . "</td>";
        echo "<td>" . htmlspecialchars($row['DataRitiro']) . "</td>";

        echo "<td>
                <form action='' method='post' style='display:inline;'>
                    <input type='hidden' name='id' value='" . htmlspecialchars($row['IDAdozione']) . "'>
                    <input type='submit' name='edit' value='Modifica'>
                </form>
                
                <form action='DB/Elimina riga.php' method='post' style='display:inline;'>
                    <input type='hidden' name='id' value='" . htmlspecialchars($row['IDAdozione']) . "'>
                    <button type='submit' onclick='return confirm(\"Sei sicuro di voler eliminare questa adozione?\");'>Elimina</button>
                </form>
              </td>";
        echo "</tr>";
    }

    echo "</table>";

    echo '<div style="text-align:left; margin: 20px;">';
    echo '<a href="CreaAdozione.php" style="display:inline-block; padding: 10px 20px; background-color: #0871a1; color: white; text-decoration: none; border-radius: 5px;">Crea Adozione</a>';
    echo '</div>';

    if (isset($_POST['edit'])) {
        $id = $_POST['id'];

        $sql = "SELECT * FROM Adozioni WHERE IDAdozione = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $adozione = $result->fetch_assoc();

        // Show form to edit adoption data: Stato and DataRitiro
        echo "<form action='' method='post'>
                <input type='hidden' name='id' value='" . htmlspecialchars($adozione['IDAdozione']) . "'>
                <label for='stato'>Stato:</label>
                <select name='stato' id='stato'>
                    <option value='0' " . ($adozione['Stato'] == 0 ? 'selected' : '') . ">Richiesta in attesa</option>
                    <option value='1' " . ($adozione['Stato'] == 1 ? 'selected' : '') . ">Approvata</option>
                    <option value='2' " . ($adozione['Stato'] == 2 ? 'selected' : '') . ">Rifiutata</option>
                </select><br><br>
                <label for='data_ritiro'>Data Ritiro:</label>
                <input type='date' name='data_ritiro' id='data_ritiro' value='" . htmlspecialchars($adozione['DataRitiro']) . "'><br><br>
                <button type='submit' name='update_adozione'>Aggiorna Adozione</button>
              </form>";
    }

    // Se viene inviato il form per aggiornare il ruolo
    if (isset($_POST['update_ruolo'])) {
        $id = $_POST['id'];
        $ruolo = $_POST['ruolo'];

        // Aggiorna il ruolo dell'utente nel database
        $sql = "UPDATE Utenti SET Ruolo = ? WHERE IDUtente = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("si", $ruolo, $id);

        if ($stmt->execute()) {
           
        } else {
            echo "<script>alert('Errore nell\'aggiornamento del ruolo.');</script>";
        }
    }

    // Se viene inviato il form per aggiornare l'adozione
    if (isset($_POST['update_adozione'])) {
        $id = $_POST['id'];
        $stato = $_POST['stato'];
        $data_ritiro = $_POST['data_ritiro'];

        $sql = "UPDATE Adozioni SET Stato = ?, DataRitiro = ? WHERE IDAdozione = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("isi", $stato, $data_ritiro, $id);

        if ($stmt->execute()) {
            echo "<script>alert('Adozione aggiornata con successo.'); window.location.href = 'GestioneAdozioni.php';</script>";
        } else {
            echo "<script>alert('Errore nell\'aggiornamento dell\'adozione.');</script>";
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
