<?php
session_start();

// Verifica se l'utente è loggato
if (!isset($_SESSION['loggato']) || $_SESSION['loggato'] !== true) {
    header("Location: index.php");
    exit;
}
$notify= $_SESSION['has_notify'] ;
// Include il file di configurazione per la connessione al database
require_once('config.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Area Personale</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="script.js"></script>
</head>

<body>

    <header>
        <div class="logo">
            <a href="area-personale.php"><img src="logo-prenotazione-removebg-preview.png" alt="Logo"></a>
        </div>
        <div>
            <h3 class="titolo-prenotazione" style="color: white;">ciao <?php echo $_SESSION['username'] ?> !</h3>

        </div>
        <div class="link">
            <a href="disconnetti.php">Disconnetti</a>
        </div>
    </header>
    <!--modale Ok-->
    <div id="myModalok" class="modal modal-ok">
        <div class="modal-content">

            <h2 class="testo-modale">Prenotazione creata con successo!</h2>
            <button class="button-modale" onclick="closeModalOk()">Chiudi</button>
        </div>
    </div>
    <!-- fine modale ok -->
    <!--modale no-->
    <div id="myModalno" class="modal modal-no">
        <div class="modal-content">

            <h2 class="testo-modale">Esiste già una prenotazione per questo intervallo di tempo. Scegli un altro orario.</h2>
            <button class="button-modale" onclick="closeModalNo()">Chiudi</button>
        </div>
    </div>
    <!-- fine modale no-->
    <div class="formbold-main-wrapper">
        <div>
            <h4>Hai le notifiche mail <strong>  <?php if($notify ==1 || $notify=='1'){
                echo "attivate";
            }else{  echo "disattivate";
            }?></strong></h4>
            <form action="disattiva_notifica.php" method="GET">
                <select name="notificami">
                   
                    <option value="1">attiva</option>
                    <option value="0">disattiva</option>
                </select>
                <input type="submit"  value="Salva"></input>
            </form>
        </div>


        <div class="formbold-form-wrapper">

            <!-- FORM PRENOTAZIONE -->
            <form action="prenotazione.php" method="POST">
                <div class="flex flex-wrap formbold--mx-3">
                </div>
                <div class="flex flex-wrap formbold--mx-3">
                    <div class="w-full sm:w-half formbold-px-3">
                        <div class="formbold-mb-5 w-full">
                            <label for="date" class="formbold-form-label">Giorno</label>
                            <input type="date" name="date" id="date" class="formbold-form-input" required />
                        </div>
                    </div>
                    <div class="w-full sm:w-half formbold-px-3">
                        <div class="formbold-mb-5">
                            <label for="start_time" class="formbold-form-label">Dalle ore</label>
                            <input type="time" name="start_time" id="start_time" class="formbold-form-input" required />
                        </div>
                    </div>
                    <div class="w-full sm:w-half formbold-px-3">
                        <div class="formbold-mb-5">
                            <label for="end_time" class="formbold-form-label">Alle ore</label>
                            <input type="time" name="end_time" id="end_time" class="formbold-form-input" required />
                        </div>
                    </div>
                </div>
                <div>
                    <button type="submit" class="formbold-btn">Prenota</button>
                </div>
            </form>
        </div>
    </div>

    <?php

    if (isset($_GET['message'])) {
        $message = htmlspecialchars($_GET['message'], ENT_QUOTES, 'UTF-8');
        echo '<script type="text/javascript">alert("' . $message . '");</script>';
    }


    $today = date('Y-m-d');
    // Query per ottenere le prenotazioni
    $sql = "SELECT utenti.username, prenotazioni.id, prenotazioni.date, prenotazioni.start_time, prenotazioni.end_time, prenotazioni.utente 
            FROM prenotazioni
            JOIN utenti ON prenotazioni.utente = utenti.id_utente
            WHERE prenotazioni.date >= ?
            ORDER BY prenotazioni.date ASC";

    // Prepara la query
    $stmt = $connessione->prepare($sql);
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($connessione->error));
    }

    // Associa i parametri
    $stmt->bind_param('s', $today);

    // Esegui la query
    $stmt->execute();

    // Ottieni il risultato
    $result = $stmt->get_result();
    if ($result === false) {
        die('Execute failed: ' . htmlspecialchars($stmt->error));
    }

    ?>

    <h1 class="titolo-prenotazione">Lista delle Prenotazioni</h1>

    <div class="prenotazioni-container">
        <?php

        // Verifica se ci sono risultati e visualizzazione delle prenotazioni
        if ($result->num_rows > 0) {
            $idUser = intval($_SESSION['id_utente']);
            while ($row = $result->fetch_assoc()) {
                $id_utente_prenot = htmlspecialchars($row['utente']);
                $link_eliminare = 'elimina-card.php?id_p=' . urlencode($row['id']) . '&id_u=' . urlencode($row['utente']);

                // Assegna un ID unico per ogni link di eliminazione
                $elementId = 'delete-' . $row['id'];

                echo "<div class='prenotazione-card'>";
                echo "<h3>Prenotazione di " . htmlspecialchars($row['username']) . "</h3>";
                echo "<p>Data: " . htmlspecialchars($row['date']) . "</p>";
                echo "<p>Dalle ore: " . htmlspecialchars($row['start_time']) . "</p>";
                echo "<p>Alle ore: " . htmlspecialchars($row['end_time']) . "</p>";
                echo "<a style='color:red' id='$elementId' href='#' onClick=\"confirmation('$link_eliminare')\">Elimina</a>";
                echo "</div>";

                // Aggiungi JavaScript per gestire la visibilità
                if ($id_utente_prenot != $idUser) {
                    echo "<script>
                        document.getElementById('$elementId').style.display = 'none';
                    </script>";
                }
            }
        } else {
            echo "<p>Non ci sono prenotazioni.</p>";
        }


        // Chiudi la connessione al database
        $connessione->close();
        ?>
    </div>
    <script>
        function confirmation(link_delete) {
            var ask = confirm('Sei sicuro/a di voler eliminare questa prenotazione?');
            if (ask) {
                // Se l'utente conferma, reindirizza all'URL di eliminazione
                window.location.href = link_delete;
            }
        }

        function openModalOk() {
            document.getElementById('myModalok').style.display = 'block';
        }

        // Funzione per chiudere il modale
        function closeModalOk() {
            document.getElementById('myModalok').style.display = 'none';
        }


        function openModalNo() {
            document.getElementById('myModalno').style.display = 'block';
        }

        // Funzione per chiudere il modale
        function closeModalNo() {
            document.getElementById('myModalno').style.display = 'none';
        }
        // Funzione per rimuovere il parametro 'p' dall'URL
        function removeParameter() {
            var url = new URL(window.location.href);
            url.searchParams.delete('p');
            window.history.replaceState({}, document.title, url.pathname);
        }

        // Attivazione del timer per rimuovere il parametro dopo 5 secondi
        setTimeout(removeParameter, 5000);
    </script>


    <?php





    if (isset($_GET["p"])) {
        if ($_GET["p"] == 'ok') {
            echo '<script>openModalOk();</script>';
        } elseif ($_GET["p"] == 'no') {
            echo '<script>openModalNo();</script>';
        }
    } ?>

</body>

</html>