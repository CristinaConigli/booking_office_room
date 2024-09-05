<?php
session_start();
require_once('config.php');
// Controlla se il token CSRF è presente e valido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        !isset($_POST['csrf_token']) || 
        $_POST['csrf_token'] !== $_SESSION['csrf_token']
        
    ) {
        die('CSRF token invalid or missing.');
    }
    
    
    $email = $connessione->real_escape_string($_POST['email']);
    $password = $connessione->real_escape_string($_POST['password']);


        $sql_select = "SELECT * FROM utenti WHERE email = '$email'";
        if ($result = $connessione->query($sql_select))
            if ($result->num_rows == 1) {
                $row = $result->fetch_array(MYSQLI_ASSOC);
                if (password_verify($password, $row['password'])) {

                    $_SESSION['loggato'] = true;
                    $_SESSION['id_utente'] = $row['id_utente'];
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['has_notify'] = $row['has_notify'];
                    $_SESSION['company'] = $row['company'];

                    header("location: area-personale.php");
                } else {
                    echo "password incorretta";
                }
            } else {
                echo "non ci sono user con questo nome";
            } else {
        echo "errore in fase di login";
    }
    
    unset($_SESSION['csrf_token']);
    $connessione->close();

}