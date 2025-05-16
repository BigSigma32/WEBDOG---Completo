<?php
session_start();
require("db_connect.php");

$userid = isset($_COOKIE['user_id']) ? (int) $_COOKIE['user_id'] : (isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : 0);
$nome = "Ospite";
$ruolo = "Guest";

if ($userid > 0) {
    $sql = "SELECT Nome, Ruolo FROM Utenti WHERE IDUtente = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $userid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        $nome = htmlspecialchars($row['Nome']);
        $ruolo = $row['Ruolo'];
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nuovoNome = htmlspecialchars($_POST['nome']);
    
    if (!empty($nuovoNome)) {
        $sql = "UPDATE Utenti SET Nome = ? WHERE IDUtente = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "si", $nuovoNome, $userid);
        if (mysqli_stmt_execute($stmt)) {
            header("Location: ../Area Personale.php");
            exit();
        } else {
            echo "<p>Errore nel modificare il nome.</p>";
        }
    } else {
        echo "<p>Il nome non può essere vuoto.</p>";
    }
}
?>

<html>
<head>
    <title>Modifica Nome - WEBDOG</title>
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
            <?php } ?>
        </div>

        <div class="login">
            <?php if ($ruolo == 'Admin' || $ruolo == 'User') { ?>
                <a href='../Area Personale.php'><img src='../img/Login/user.jpg' alt='Foto profilo'></a>
            <?php } else { ?>
                <a href='../Login1.php'><img src='../img/Login/user.jpg' alt='Foto profilo'></a>
            <?php } ?>
        </div>
    </div>

    <br><br><br>

    <center>
        <h1 style="font-family:cursive">Modifica il tuo Nome</h1>

        <form method="POST">
            <div class="input-box">
                <label for="nome">Nuovo Nome:</label>
                <input type="text" id="nome" name="nome" required value="<?php echo $nome; ?>" style="width: 50%; color:rgb(0, 0, 0); height: 5%; padding: 2px;">
            </div>
            <button type="submit" class="input-box" style="width: 50%;  padding: 10px;">Salva Modifiche</button>
        </form>

        <a href="../Area Personale.php">Torna alla tua area personale</a>
    </center>
</body>
</html>
