<?php
    require("db_connect.php");
    session_start();

    $idutente = $_COOKIE["user_id"];
    $idcane = $_POST["id"];
    $date = date("Y-m-d");

    if(!isset($_COOKIE["user_id"])) {
        echo "<script type='text/javascript'>alert('È necessario eseguire il login prima di poter accedere')</script>";
        header("Location: ../Galleria.php");
    } else {
        $sql = "SELECT IDCane, Stato FROM Cani WHERE IDCane = $idcane";
        $data = mysqli_query($con, $sql);
        if(!$data) {
            die();
        }
        foreach($data as $i => $row) {
            if($row["Stato"] != 0) {
                header("Location: ../ConfermaAdozioneADistanza.php?id=caneadottato");
            } else {
                $sql = "INSERT INTO AdozioniADistanza
                        (IDfUtente, IDfCane, DataAdozione) VALUES 
                        ($idutente, $idcane, '$date')";

                if(!mysqli_query($con, $sql)) {
                    echo "Impossibile eseguire 1<br>";
                }

                $sql = "UPDATE Cani SET Stato = 3
                        WHERE IDCane = $idcane";
                
                if(!mysqli_query($con, $sql)) {
                    echo "Impossibile eseguire 2<br>";
                }

                header("Location: ../ConfermaAdozioneADistanza.php?id=$idcane");

            }
        }
    }
?>