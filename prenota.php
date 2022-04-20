<?php
include "config/dbConnect.php";

if (isset($_GET['data'])) {
    $data = $_GET['data'];


    $dataAppuntamento = date("d-m-Y", strtotime($data));
}
?>


<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <link rel="stylesheet" href="/style/style.css">
    <title>Modulo di prenotazione</title>
</head>

<body>
    <div class="titolo">Prenotazione appuntamento per il giorno <span class="dataAppuntamento"><?php echo $dataAppuntamento; ?></span> </div>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                Dettagli paziente
            </div>
            <div class="card-body">

                <form id='formDati'>
                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <label for="nome">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cognome">Cognome</label>
                            <input type="text" class="form-control" name="cognome" id="cognome" placeholder="Cognome" required>
                        </div>

                    </div>
                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="telefono">Telefono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Telefono" required>
                        </div>

                    </div>

                </form>

            </div>
        </div>

        <?php
        function timeSlot($tempoSeduta, $oraInizio, $oraFine)
        {
            $oraInizio = new DateTime($oraInizio);
            $oraFine = new DateTime($oraFine);
            $tempoSeduta = new DateInterval("PT" . $tempoSeduta . "M");
            $slots = array();


            for ($intStart = $oraInizio; $intStart < $oraFine; $intStart->add($tempoSeduta)) {
                $endPeriod = clone $intStart;
                $endPeriod->add($tempoSeduta);
                if ($endPeriod > $oraFine) {
                    break;
                }

                $slots[] = $intStart->format("H:i") . " - " . $endPeriod->format("H:i");
            }

            return $slots;
        }

        ?>

        <div class="card mt-4">
            <div class="card-header">Orario</div>

            <div class="card-body">
                <h4 style="text-align:center">Mattina</h4>
                <div class="pt-1" style="display: flex; justify-content: space-between; align-items: center; flex-direction: row; flex-wrap: wrap;">

                    <?php
                    //Faccio un check dei timeslot liberi
                    $dati = array();
                    $queryTimeSlot = mysqli_query($connessione, "SELECT * FROM `appuntamenti` WHERE `approvato` = 1 AND `dataPrenotazione` = '$data'");
                    while ($leggo = mysqli_fetch_array($queryTimeSlot)) {
                        $dati[] = $leggo['timeSlot'];
                    }

                    $timeslots = timeSlot(30, "9:30", "13:00");
                    foreach ($timeslots as $ts) {
                        if (!in_array($ts, $dati)) {

                            echo "<button class='btn btn-success book mt-2' onclick='prenotaOrario(`$ts; ?>`)'> $ts</button>";
                        } else {

                            echo "<button class='btn btn-danger book mt-2' disabled>$ts</button>";
                        }
                    } ?>
                </div>
                <hr class="spaziatore">
                <h4 style="text-align:center">Pomeriggio</h4>
                <div class="pt-1" style="display: flex; justify-content: space-between; align-items: center; flex-direction: row; flex-wrap: wrap;">

                    <?php $timeslots = timeSlot(30, "15:30", "19:00");
                    foreach ($timeslots as $ts) {
                    ?>
                        <button class="btn btn-success book mt-2 " onclick="prenotaOrario(`<?php echo $ts; ?>`)"><?php echo $ts; ?></button>


                    <?php } ?>
                </div>
            </div>

        </div>


</body>


</html>

<script src="/func/js/func.js"></script>