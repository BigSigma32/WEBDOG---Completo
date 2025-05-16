<?php
session_start();
require("db_connect.php");

$userid = 0;
if (isset($_SESSION['user_id'])) {
    $userid = (int)$_SESSION['user_id'];
} elseif (isset($_COOKIE['user_id'])) {
    $userid = (int)$_COOKIE['user_id'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $messaggio = isset($_POST['messaggio']) ? trim($_POST['messaggio']) : '';
    $importo = isset($_POST['importo']) ? floatval($_POST['importo']) : 0;

    if ($userid <= 0) {
        $error = "Utente non autenticato.";
    } elseif ($importo <= 0) {
        $error = "Importo non valido.";
    } else {
        $dataDonazione = date('Y-m-d');

        $sql = "INSERT INTO Donazioni (IDfUtente, Importo, DataDonazione, Messaggio) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "idss", $userid, $importo, $dataDonazione, $messaggio);
            if (mysqli_stmt_execute($stmt)) {
                header("Location: ../Donazioni.php?status=success");
                exit();
            } else {
                $error = "Errore durante l'inserimento della donazione.";
            }
        } else {
            $error = "Errore nella preparazione della query.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Conferma Donazione</title>
    <link rel="stylesheet" href="../estetica.css">
</head>
<body>
    <h1>Conferma Donazione</h1>
    <?php if (isset($error)): ?>
        <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form method="post" action="Dona.php">
        <label for="importo">Importo (€):</label><br>
        <input type="number" id="importo" name="importo" step="0.01" min="0.01" required><br><br>

        <label for="messaggio">Messaggio personalizzato:</label><br>
        <input type="text" id="messaggio" name="messaggio"><br><br>

        <input type="submit" value="Conferma Donazione">
    </form>
</body>
</html>
