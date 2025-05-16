<?php
session_start();
require("db_connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $_SESSION['usernameErr'] = "";

    // Prepara la query per verificare l'utente
    $stmt = $con->prepare("SELECT IDUtente, password, Ruolo FROM Utenti WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        
        // Verifica la password
        if (password_verify($password, $row['password'])) {
            // Salva i dati nella sessione
            $_SESSION['user_id'] = $row['IDUtente'];
            $_SESSION['role'] = $row['Ruolo'];

            // Se l'utente ha selezionato "Ricordami", salva i cookie
            if (isset($_POST['remember'])) {
                setcookie("user_id", $row['IDUtente'], time() + (30 * 24 * 60 * 60), "/");  // Cookie per l'ID utente
                setcookie("role", $row['Ruolo'], time() + (30 * 24 * 60 * 60), "/");  // Cookie per il ruolo
            } else {
                // Se "Ricordami" non è selezionato, cancella i cookie (se esistono)
                setcookie("user_id", "", time() - 3600, "/");
                setcookie("role", "", time() - 3600, "/");
            }

            // Redirezione in base al ruolo
            if ($row['Ruolo'] == "Admin") {
                header("Location: ../Home.php");
            } elseif ($row['Ruolo'] == "User") {
                header("Location: ../Home.php");
            } else {
                header("Location: ../Home.php");
            }
            exit();  // Uscita dopo il redirect
        } else {
            echo "<script type='text/javascript'>alert('Password sbagliata!!!'); location.href = '../Login1.php';</script>";
        }
    } else {
        echo "<script type='text/javascript'>alert('Email non trovata!!!'); location.href = '../Login1.php';</script>";
    }
}
?>
