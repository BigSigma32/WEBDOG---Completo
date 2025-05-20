<?php
require("db_connect.php");

if(!isset($_POST["password"], $_POST["confermaPassword"], $_POST["email"], $_POST["nome"], $_POST["cognome"], $_POST["numTelefono"], $_POST["citta"], $_POST["CAP"], $_POST["via"], $_POST["nro"])) {
    exit("Dati mancanti.");
}

$password = $_POST["password"];
$confermaPassword = $_POST["confermaPassword"];
$email = $_POST["email"];
$nome = $_POST["nome"];
$cognome = $_POST["cognome"];
$numTelefono = $_POST["numTelefono"];
$provincia = $_POST["provincia"];
$citta = $_POST["citta"];
$CAP = $_POST["CAP"];
$via = $_POST["via"];
$nro = $_POST["nro"];

if($password != $confermaPassword) {
    exit("Le password non corrispondono.");
}

// Hash della password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);


$email = mysqli_real_escape_string($con, $email);
$nome = mysqli_real_escape_string($con, $nome);
$cognome = mysqli_real_escape_string($con, $cognome);
$numTelefono = mysqli_real_escape_string($con, $numTelefono);
$provincia = mysqli_real_escape_string($con, $provincia);
$citta = mysqli_real_escape_string($con, $citta);
$CAP = mysqli_real_escape_string($con, $CAP);
$via = mysqli_real_escape_string($con, $via);
$nro = mysqli_real_escape_string($con, $nro);

$sql = "SELECT * FROM Utenti WHERE Email = ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    echo "<script type='text/javascript'>alert('Password sbagliata!!!'); location.href = '../Sign In.php';</script>;";
    exit();
}

$Ruolo = "User"; 

$sql = "INSERT INTO Utenti (Nome, Cognome, Email, Password, NumeroTelefono, Provincia, Città, Via, NumeroCivico, CAP, Ruolo)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; 

$stmt = mysqli_prepare($con, $sql);


if (mysqli_stmt_bind_param($stmt, "sssssssssss", $nome, $cognome, $email, $hashed_password, $numTelefono, $provincia, $citta, $via, $nro, $CAP, $Ruolo)) {
   
    if (mysqli_stmt_execute($stmt)) {
     
        setcookie("Email", $email, time() + (86400 * 30), "/");

        echo "Utente registrato con successo.";
        header("Location: ../Login1.php");
    } else {
        echo "Errore nell'esecuzione della query: " . mysqli_error($con);
    }
} else {
    echo "Errore dei parametri.";
}


mysqli_stmt_close($stmt);
mysqli_close($con);
?>
