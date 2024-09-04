<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="login.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    
<img src="logo-prenotazione-removebg-preview.png" alt="">

<form method="POST" action="nuova-registrazione.php">
  <div class="container">
    <h1>Registrazione</h1>
    <hr>

    <div>
    <label for="email"><b>Email</b></label><br>
    <input type="email" name="email" id="email" required>
    </div>

    <div>
    <label for="company"><b>azienda</b></label><br>
    <input type="text" name="company" id="company" required>
    </div>

    <div>
    <label for="username"><b>username</b></label><br>
    <input type="text" name="username" id="username" required>
    </div>

    <div>
    <label for="psw"><b>Password</b></label><br>
    <input type="password" name="password" id="password" required>
    </div>

    <button type="submit" class="bottone-input">Registrati</button>
  </div>

  <div class="container signin">
    <p>Hai già un account? <a href="index.php">Accedi</a></p>
  </div>
</form> 


</body>
</html>