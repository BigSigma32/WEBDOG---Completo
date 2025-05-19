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

if ($ruolo != "Admin") {
    header('Location: Home.php');
    exit();
}

require("DB/db_connect.php");
$con = mysqli_connect($db_hostname, $db_username, $db_password, $db_name);

if (!$con) {
    die("Connessione al database fallita: " . mysqli_connect_error());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idUtente = $_POST['idUtente'];
    $idCane = $_POST['idCane'];
    $dataRichiesta = date("Y-m-d");
    $stato = 0; // default to "Richiesta in attesa"

    $sql = "INSERT INTO Adozioni (IDfUtente, IDfCane, DataRichiesta, Stato) VALUES (?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("iisi", $idUtente, $idCane, $dataRichiesta, $stato);

    if ($stmt->execute()) {
        header("Location: GestioneAdozioni.php");
        exit();
    } else {
        $error = "Errore durante la creazione dell'adozione: " . $stmt->error;
    }
}

// Fetch users and dogs for the form
$usersResult = mysqli_query($con, "SELECT IDUtente, Nome, Cognome FROM Utenti ORDER BY Nome");
$dogsResult = mysqli_query($con, "SELECT IDCane, Nome FROM Cani WHERE Stato = 0 ORDER BY Nome");

?>

<html>
<head>
    <title>Crea Adozione - WEBDOG</title>
    <link rel="stylesheet" href="estetica.css">
    <style>
        form {
            max-width: 400px;
            margin: 30px auto;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        select, input[type="submit"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        input[type="submit"] {
            background-color: #0871a1;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #005f7f;
        }
        .error {
            color: red;
            margin-bottom: 15px;
            text-align: center;
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
            } else {
                echo "<a href='Login1.php'><img src='img/Login/user.jpg' alt='Foto profilo' class='fotoprofilo'></a>";
            }
            ?>
        </div>
    </div>

    <h2 style="text-align:center; margin-top: 30px;">Crea Nuova Adozione</h2>

    <?php if (isset($error)) { echo "<div class='error'>" . htmlspecialchars($error) . "</div>"; } ?>

    <form method="post" action="CreaAdozione.php">
        <label for="idUtente">Seleziona Utente:</label>
        <select name="idUtente" id="idUtente" required>
            <option value="">-- Seleziona Utente --</option>
            <?php while ($user = mysqli_fetch_assoc($usersResult)) { ?>
                <option value="<?php echo htmlspecialchars($user['IDUtente']); ?>">
                    <?php echo htmlspecialchars($user['Nome'] . " " . $user['Cognome']); ?>
                </option>
            <?php } ?>
        </select>

        <label for="idCane">Seleziona Cane:</label>
        <select name="idCane" id="idCane" required>
            <option value="">-- Seleziona Cane --</option>
            <?php while ($dog = mysqli_fetch_assoc($dogsResult)) { ?>
                <option value="<?php echo htmlspecialchars($dog['IDCane']); ?>">
                    <?php echo htmlspecialchars($dog['Nome']); ?>
                </option>
            <?php } ?>
        </select>

        <input type="submit" value="Crea Adozione">
    </form>
</body>
</html>
