<?php
session_start();

require_once('config.php');

$userId = $_SESSION['id_utente']; 
$notify = $_GET['notificami']; // Supponiamo che questo valore venga passato tramite GET

if ($notify == "0" || $notify == "1") {
    // Aggiorna il database
    $stmt = $connessione->prepare("UPDATE utenti SET has_notify = ? WHERE id_utente = ?");
    $stmt->bind_param("ii", $notify, $userId);
    $stmt->execute();
    $stmt->close();

    // Aggiorna la variabile di sessione
    $_SESSION['has_notify'] = $notify;

    // Reindirizza l'utente a una pagina di conferma o alla pagina personale
    header("Location: area-personale.php");
    exit();
} else {
    // Gestisci il caso in cui 'notificami' abbia un valore non valido
    echo "Opzione non valida.";
}
   