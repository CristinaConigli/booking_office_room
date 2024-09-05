<?php

require_once('config.php');


$email = $connessione->real_escape_string($_POST['email']);
$password = $connessione->real_escape_string($_POST['password']);

if($_SERVER["REQUEST_METHOD"] == "POST"){
   
  $sql_select = "SELECT * FROM utenti WHERE email = '$email'";
  if($result = $connessione->query($sql_select))
     if($result->num_rows == 1){
        $row = $result->fetch_array(MYSQLI_ASSOC);
        if(password_verify($password, $row['password'])){
            session_start();
            
            $_SESSION['loggato'] = true;
            $_SESSION['id_utente'] = $row['id_utente'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['has_notify'] = $row['has_notify'];
            $_SESSION['company'] = $row['company'];

            header("location: area-personale.php");
        }else{
            echo "password incorretta";
        }
     }else{
        echo "non ci sono user con questo nome";
     }

}else {
    echo "errore in fase di login";
}

$connessione->close();

