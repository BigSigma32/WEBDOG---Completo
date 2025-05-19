<?php
    require("db_connect.php");
    session_start();

    $idutente = $_COOKIE["user_id"];
    $idcane = $_GET["id"];
    $date = date("Y-m-d");

    $sql = "SELECT IDCane, Stato FROM Cani WHERE IDCane = $idcane";
    $data = mysqli_query($con, $sql);

    foreach($data as $i => $row) {
        if($row["Stato"] == 1 || $row["Stato"] == 2) {
            header("Location: ../AdozioneConfermata.php?id=caneadottato");
        } else {
            $sql = "UPDATE Cani SET Stato = 1
                    WHERE IDCane = $idcane";
            
            if(!mysqli_query($con, $sql)) {
                echo "Impossibile eseguire 2<br>";
            }

            $sql = "INSERT INTO Adozioni
                    (IDfUtente, IDfCane, DataRichiesta) VALUES 
                    ($idutente, $idcane, '$date')";

            if(!mysqli_query($con, $sql)) {
                echo "Impossibile eseguire 1<br>";
            }

            $x = date("Y-m-d");

            $sql = "UPDATE AdozioniADistanza SET TermineAdozione = $x";

            if(!mysqli_query($con, $sql)) {
                echo "Impossibile eseguire 1<br>";
            }
            header("Location: ../AdozioneConfermata.php?id=$idcane");
        }
    }
?>