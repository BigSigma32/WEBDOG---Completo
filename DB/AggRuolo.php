<?php
require("db_connect.php");

$con = mysqli_connect($db_hostname, $db_username, $db_password, $db_name);

if (!$con) {
    die("Connessione al database fallita: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomeRuolo = isset($_POST['nomeRuolo']) ? trim($_POST['nomeRuolo']) : '';
    $descrizione = isset($_POST['descrizioneRuolo']) ? trim($_POST['descrizioneRuolo']) : '';

    if (empty($nomeRuolo)) {
        echo "Il nome del ruolo è obbligatorio.";
        exit;
    }

    $sql = "INSERT INTO Incarichi (Nome, Descrizione) VALUES (?, ?)";
    $stmt = $con->prepare($sql);
    if (!$stmt) {
        echo "Errore nella preparazione della query: " . $con->error;
        exit;
    }
    $stmt->bind_param("ss", $nomeRuolo, $descrizione);

    if ($stmt->execute()) {
        echo "Ruolo creato con successo.";
        header("Location: ../GestioneAddetti.php");
    } else {
        echo "Errore durante la creazione del ruolo: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Metodo non consentito.";
}

$con->close();
?>
