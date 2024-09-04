<?php
session_start(); // Assicurati di chiamare session_start() all'inizio del file
require_once('config.php');

if (isset($_SESSION['id_utente'])) {
    $idUser = intval($_SESSION['id_utente']); // Assicurati che l'ID utente sia un numero intero

    if (isset($_GET['id_p'])) {
        $idCard = intval($_GET['id_p']); 
        $id_user_prenotazione=intval($_GET['id_u']);

        if($idUser != $id_user_prenotazione){
            header("Location: area-personale.php?message=Non puoi eliminare la prenotazione di qualcun altro!");
            exit();       
        }

        $sql = "DELETE FROM prenotazioni WHERE id = ? AND utente = ?";
        $stmt = $connessione->prepare($sql);

        if ($stmt) {
            $stmt->bind_param('ii', $idCard, $idUser);
            if ($stmt->execute()) {
                $stmt->close();
                $connessione->close();
                header("Location: area-personale.php");
                exit(); // Assicurati di uscire dopo il redirect

            } else {
                echo "Errore nell'eliminazione della prenotazione: " . $stmt->error;
            }
        } else {
            echo "Errore nella preparazione della query: " . $connessione->error;
        }
    } else {
        echo "La card non è stata eliminata perché l'ID non è stato fornito.";
    }
} else {
    echo "L'ID utente non è definito nella sessione.";
}


?>
