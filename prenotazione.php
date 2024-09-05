<?php
session_start();

if (isset($_SESSION['loggato']) && $_SESSION['loggato'] === true) {
    $userId = $_SESSION['id_utente']; // Accedi all'ID utente
    $username = $_SESSION['username'];
    $fName = $_SESSION['username'];
    $has_notify = $_SESSION['has_notify'];
}

require_once('config.php');

if ($connessione->connect_error) {
    die("Connessione al database fallita: " . $connessione->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $opzione_sala = $_POST['sala'];
    $id_sala = "";
    if ($opzione_sala == "small") {
        $id_sala = 1;
    } else if ($opzione_sala == "big") {
        $id_sala = 2;
    }
    $date = $_POST['date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $id_castato = (int)$userId;

    $check_sql = "SELECT * FROM prenotazioni 
                  WHERE date = ? 
                  AND (
                      (start_time <= ? AND end_time > ?) OR 
                      (start_time < ? AND end_time >= ?)
                  ) AND id_sala = ?";
    $stmt = $connessione->prepare($check_sql);
    $stmt->bind_param('sssssi', $date, $start_time, $start_time, $end_time, $end_time, $id_sala);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        //mando messaggio modale di errore
        header("Location: area-personale.php?p=no");
    } else {
        // Query per ottenere il nome della sala
        $sala_sql = "SELECT name FROM sale WHERE id = ?";
        $stmt_sala = $connessione->prepare($sala_sql);
        $stmt_sala->bind_param('i', $id_sala);
        $stmt_sala->execute();
        $result_sala = $stmt_sala->get_result();

        if ($result_sala->num_rows > 0) {
            $row_sala = $result_sala->fetch_assoc();
            $stampa_sala = $row_sala['name'];
        } else {
            $stampa_sala = "";
        }
        $insert_sql = "INSERT INTO prenotazioni ( date, start_time, end_time, utente, id_sala) 
                       VALUES ( ?, ?, ?,?,?)";
        $stmt = $connessione->prepare($insert_sql);
        $stmt->bind_param('sssii', $date, $start_time, $end_time, $id_castato, $id_sala);

        $timestamp_date = strtotime($date);
        $eu_date = date('d-m-Y', $timestamp_date);

        if ($stmt->execute()) {
            //mando messaggio modale di riuscita
            header("Location: area-personale.php?p=ok");

            //inserire invio mail
            if ($has_notify == '1' || $has_notify == 1) {
                // Esegui la query per ottenere tutte le email degli utenti
                $email_query = "SELECT email, company FROM utenti WHERE has_notify=1";
                $result = $connessione->query($email_query);

                // Verifica che la query abbia restituito dei risultati
                if ($result->num_rows > 0) {
                    // Itera su ciascun risultato
                    while ($row = $result->fetch_assoc()) {
                        $to = $row['email'];

                        $subject = "Nuova prenotazione sala " . $stampa_sala;

                        $message = "Ciao,\n\nÈ stata creata una nuova prenotazione per il giorno $eu_date dalle $start_time alle $end_time, da " . $row['company'] . ".\n\nGrazie!";
                        $headers = "From: noreply@prenotazione-sala.com";

                        // Invia l'email a ciascun indirizzo
                        mail($to, $subject, $message, $headers);
                    }
                } else {
                    echo "Nessuna email trovata.";
                }
            }
        }
        $stmt->close();
    }
    $connessione->close();
}
