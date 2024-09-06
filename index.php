<?php
// Avvia la sessione prima di modificare i parametri dei cookie
session_start();

// Imposta un percorso valido per il cookie della sessione
session_set_cookie_params([
    'path' => '/',
    'httponly' => true,
    'secure' => isset($_SERVER['HTTPS']), // Usa solo su HTTPS
    'samesite' => 'Strict',
]);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Genera il token CSRF se non esiste
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$csrf_token = $_SESSION['csrf_token'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sala Riunioni</title>
  <link rel="stylesheet" href="login.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">

</head>

<body>
 

  <img src="logo-prenotazione-removebg-preview.png" alt="">

   <form method="POST" action="login-nuova.php">
  <!-- test-->
   <!--<form method="POST" action="">-->

    <div class="container">
      <h1>Accedi</h1>

      <hr>

      <div>
        <label for="email"><b>email</b></label><br>
        <input type="email" name="email" id="email" required>
      </div>

      <div>
        <label for="psw"><b>Password </b></label><br>
        <input type="password" name="password" id="password" required>
		 


      </div>
       <!-- Token CSRF -->
		<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">

      

      <button type="submit" class="bottone-input">Accedi</button>


      <!-- <div class="container signin">
    <p>Non hai ancora un account? <a href="registrazione-nuova.php">Registrati</a></p>
    </div> -->
    </div>
  </form>



</body>

</html>