<?php



require_once('config.php');


$email = $connessione->real_escape_string($_POST['email']);
$company= $connessione->real_escape_string($_POST['company']);
$username = $connessione->real_escape_string($_POST['username']);
$password = $connessione->real_escape_string($_POST['password']);
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO utenti (email, username, password, company) VALUES ('$email', '$username', '$hashed_password','$company')";
if($connessione->query($sql) === true){
    header("Location: area-personale.php");
} else {
    echo "Errore in fase di registrazione $sql." . $connessione->error;
}



