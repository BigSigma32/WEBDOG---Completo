<?php
session_start();
$ruolo = "";

if (isset($_COOKIE['role'])) {
    $ruolo = $_COOKIE['role'];
} elseif (isset($_SESSION['role'])) {
    $ruolo = $_SESSION['role'];
}

if (!isset($_SESSION['user_id']) && !isset($_COOKIE['user_id'])) {
    $ruolo = "User";
}

if ($ruolo != "Admin") {header('Location: Home.php');}
?>

<html>
<head>
    <title>Gestione Utenti - WEBDOG</title>
    <link rel="stylesheet" href="estetica.css">
    <style>
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

    $sql = "SELECT * FROM Utenti ORDER BY ruolo";
    $result = mysqli_query($con, $sql);

    if (!$result) {
        die("Errore nella query: " . mysqli_error($con));
    }

    echo "<table class='table'>";
    echo "<tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Cognome</th>
            <th>Email</th>
            <th>Numero Telefono</th>
            <th>Provincia</th>
            <th>Città</th>
            <th>Via</th>
            <th>Numero Civico</th>
            <th>CAP</th>
            <th>Ruolo</th>
            <th>Azioni</th>
          </tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['IDUtente']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Nome']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Cognome']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Email']) . "</td>";
        echo "<td>" . htmlspecialchars($row['NumeroTelefono']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Provincia']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Città']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Via']) . "</td>";
        echo "<td>" . htmlspecialchars($row['NumeroCivico']) . "</td>";
        echo "<td>" . htmlspecialchars($row['CAP']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Ruolo']) . "</td>";
        
        echo "<td>
                <form action='' method='post' style='display:inline;'>
                    <input type='hidden' name='id' value='" . htmlspecialchars($row['IDUtente']) . "'>
                    <input type='submit' name='edit' value='Modifica'>
                </form>
                
                <form action='DB/Elimina riga.php' method='post' style='display:inline;'>
                    <input type='hidden' name='id' value='" . htmlspecialchars($row['IDUtente']) . "'>
                    <button type='submit' onclick='return confirm(\"Sei sicuro di voler eliminare questo utente?\");'>Elimina</button>
                </form>
              </td>";
        echo "</tr>";
    }

    echo "</table>";

    if (isset($_POST['edit'])) {
        $id = $_POST['id'];

        $sql = "SELECT * FROM Utenti WHERE IDUtente = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        echo "<form action='' method='post'>
                <input type='hidden' name='id' value='" . htmlspecialchars($user['IDUtente']) . "'>
                <label for='ruolo'>Ruolo:</label>
                <select name='ruolo' id='ruolo'>
                    <option value='Admin' " . ($user['Ruolo'] == 'Admin' ? 'selected' : '') . ">Admin</option>
                    <option value='User' " . ($user['Ruolo'] == 'User' ? 'selected' : '') . ">User</option>
                </select>
                <button type='submit' name='update_ruolo'>Aggiorna Ruolo</button>
              </form>";
    }

    if (isset($_POST['update_ruolo'])) {
        $id = $_POST['id'];
        $ruolo = $_POST['ruolo'];

        $sql = "UPDATE Utenti SET Ruolo = ? WHERE IDUtente = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("si", $ruolo, $id);

        if ($stmt->execute()) {
        } else {
            echo "<script>alert('Errore nell\'aggiornamento del ruolo.');</script>";
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
