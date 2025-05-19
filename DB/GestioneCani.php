<?php
ob_start();
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
    $ruolo = "User";
}

if ($ruolo != "Admin") {header('Location: ../Home.php');}
?>

<html>
<head>
    <title>Gestione Utenti - WEBDOG</title>
    <!-- Collega il file CSS per gli stili generali e per la tabella -->
    <link rel="stylesheet" href="../estetica.css"> <!-- Assicurati che il percorso sia corretto -->
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
                <a href="GestioneCani.php">Gestione Cani</a>
                <a href="../GestioneAdozioni.php">Gestione Adozioni</a>
            <?php } ?>
        </div>

        <div class="login">
            <?php 
            if ($ruolo == 'Admin' || $ruolo == 'User') {
                echo "<a href='Area Personale.php'><img src='../img/Login/user.jpg' alt='Foto profilo' class='fotoprofilo'></a>";
            } elseif($ruolo != 'User' && $ruolo != 'Admin') {
                echo "<a href='Login1.php'><img src='../img/Login/user.jpg' alt='Foto profilo' class='fotoprofilo'></a>";
            } ?>
        </div>
    </div>

    <br><br><br>

    
    <?php
    require("db_connect.php");
    $con = mysqli_connect($db_hostname, $db_username, $db_password, $db_name);

    if (!$con) {
        die("Connessione al database fallita: " . mysqli_connect_error());
    }

    // Recupera i cani dal database
    $sql = "SELECT * FROM Cani ORDER BY IDCane";
    $result = mysqli_query($con, $sql);

    if (!$result) {
        die("Errore nella query: " . mysqli_error($con));
    }

    echo "<table class='table'>";
    echo "<tr>
            
            <th>ID Cane</th>
            <th>Nome</th>
            <th>Descrizione</th>
            <th>Età</th>   
            <th>Vaccinato</th>
            <th>microchip</th>
            <th>Azioni</th>
          </tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        //echo "<td>" . htmlspecialchars($row['Img1']) . "</td>";
        echo "<td>" . htmlspecialchars($row['IDCane']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Nome']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Descrizione']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Eta']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Vaccinato']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Microchip']) . "</td>";
        echo "<td>
                <form action='' method='post' style='display:inline;'>
                    <input type='hidden' name='id' value='" . htmlspecialchars($row['IDCane']) . "'>
                    <input type='submit' name='edit' value='Modifica'>
                </form>
                
                <form action='DB/EliminaCane.php' method='post' style='display:inline;'>
                    <input type='hidden' name='id' value='" . htmlspecialchars($row['IDCane']) . "'>
                    <button type='submit' onclick='return confirm(\"Sei sicuro di voler eliminare questo cane?\");'>Elimina</button>
                </form>
              </td>";
        echo "</tr>";
    }

    echo "</table>";

    if (isset($_POST['edit'])) {
        $id = $_POST['id'];

        // Recupera i dati del cane
        $sql = "SELECT * FROM Cani WHERE IDCane = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $cane = $result->fetch_assoc();

        echo "<form action='' method='post'>
                <input type='hidden' name='id' value='" . htmlspecialchars($cane['IDCane']) . "'>
                <label for='nome'>Nome:</label>
                <input type='text' name='nome' id='nome' value='" . htmlspecialchars($cane['Nome']) . "' required>
                <label for='descrizione'>Descrizione:</label>
                <input type='text' name='descrizione' id='descrizione' value='" . htmlspecialchars($cane['Descrizione']) . "' required>
                <label for='eta'>Età:</label>
                <input type='number' name='eta' id='eta' value='" . htmlspecialchars($cane['Eta']) . "' required>
                <label for='Vaccinato'>Vacinato:</label>
                <input type='number' name='vaccinato' id='vaccinato' value='" . htmlspecialchars($cane['Vaccinato']) . "' required>
                <label for='Microchip'>Microchip:</label>
                <input type='number' name='microchip' id='microchip' value='" . htmlspecialchars($cane['Microchip']) . "' required>
                <button type='submit' name='update_cane' style='padding: 8px 16px; font-size: 16px; border-radius: 5px; background-color: #007bff; color: white; border: none; cursor: pointer; transition: background-color 0.3s;'>Aggiorna Cane</button>
              </form>";
    }

    // Se viene inviato il form per aggiornare il cane
    if (isset($_POST['update_cane'])) {
        $id = $_POST['id'];
        $nome = $_POST['nome'];
        $descrizione = $_POST['descrizione'];
        $eta = $_POST['eta'];
        $Vaccinato = $_POST['vaccinato'];
        $Microchip = $_POST['microchip'];

        // Aggiorna i dati del cane nel database
        $sql = "UPDATE Cani SET Nome = ?, Descrizione = ?, Eta = ?, Vaccinato = ?, Microchip = ? WHERE IDCane = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ssiiii", $nome, $descrizione, $eta, $Vaccinato, $Microchip, $id);

        if ($stmt->execute()) {
            // Redirect per aggiornare la pagina dopo l'aggiornamento
            header("Location: GestioneCani.php");
            
        } else {
            echo "<script>alert('Errore nell\'aggiornamento del cane.');</script>";
        }
    }
    ?>
    
    <form action="../Aggiungi cane.php" style="display:inline;">
        <input type="submit" name="AggCane" value="Aggiungi Cane" class="btn btn-primary" style="padding: 8px 16px; font-size: 16px; border-radius: 5px; background-color: #28a745; color: white; border: none; cursor: pointer; transition: background-color 0.3s;">
    </form>

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
