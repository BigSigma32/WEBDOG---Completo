<?php
require("db_connect.php");

$con = mysqli_connect($db_hostname, $db_username, $db_password, $db_name);

if (!$con) {
    die("Connessione al database fallita: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
    $dataNascita = isset($_POST['dataNascita']) ? trim($_POST['dataNascita']) : '';
    $idIncarico = isset($_POST['idIncarico']) ? intval($_POST['idIncarico']) : 0;

    if (empty($nome) || empty($dataNascita) || $idIncarico <= 0) {
        echo "Tutti i campi sono obbligatori.";
        exit;
    }

    $sql = "INSERT INTO Addetti (Nome, DataNascita, IdfIncarico) VALUES (?, ?, ?)";
    $stmt = $con->prepare($sql);
    if (!$stmt) {
        echo "Errore nella preparazione della query: " . $con->error;
        exit;
    }
    $stmt->bind_param("ssi", $nome, $dataNascita, $idIncarico);

    if ($stmt->execute()) {
        echo "Addetto registrato con successo.";
        header("Location: ../GestioneAddetti.php");
    } else {
        echo "Errore durante la registrazione: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Metodo non consentito.";
}

$con->close();
?>
