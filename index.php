<?php
include './config/dbConnect.php';

function buildTabella($month, $year)
{
    //Creo array contenente i giorni 
    $daysOfWeek = array('Domenica', 'Lunedì', 'Martedì', 'Mercoledì', 'Giovedì', 'Venerdì', 'Sabato');
    //Creo array contenente i mesi
    $months  = array('Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre');

    //Check del primo giorno del mese corrente
    $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);

    // Numero dei giorni del mese corrente
    $numberDays = date('t', $firstDayOfMonth);

    // Retrieve some information about the first day of the
    // month in question.
    $datacomp = getdate($firstDayOfMonth);

    // Recupero il mese
    $monthNumber = $datacomp['mon'];

    //Check del giorno della settimana
    $dayOfWeek = $datacomp['wday'];

    // Creo la tabella

    $actualMonth = $months[$monthNumber - 1];
    $tabellaCalendario = "<table class='table table-bordered'>";
    $tabellaCalendario .= "<center><h2>$actualMonth  $year</h2>";
    $tabellaCalendario .= " <a class='btn btn-xs btn-primary' href='?mese=" . date('m') . "&anno=" . date('Y') . "'>Mese corrente</a> ";
    $tabellaCalendario .= "<a class='btn btn-xs btn-primary' href='?mese=" . date('m', mktime(0, 0, 0, $month + 1, 1, $year)) . "&anno=" . date('Y', mktime(0, 0, 0, $month + 1, 1, $year)) . "'>Prossimo mese</a></center><br>";
    $tabellaCalendario .= "<tr>";

    // Creo gli header della tabella

    foreach ($daysOfWeek as $day) {
        $tabellaCalendario .= "<th  class='header'>$day</th>";
    }
    // Creo il resto del tabellaCalendarioio
    // Inizializzo il contatore dei giorni
    $currentDay = 1;
    $tabellaCalendario .= "</tr><tr>";

    // Creo 7 colonne

    if ($dayOfWeek > 0) {
        for ($k = 0; $k < $dayOfWeek; $k++) {
            $tabellaCalendario .= "<td  class='empty'></td>";
        }
    }


    $month = str_pad($month, 2, "0", STR_PAD_LEFT);

    while ($currentDay <= $numberDays) {

        // Alla 7 colonna creo la nuova riga.

        if ($dayOfWeek == 7) {

            $dayOfWeek = 0;
            $tabellaCalendario .= "</tr><tr>";
        }

        $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
        $date = "$year-$month-$currentDayRel";

        $dayname = strtolower(date('l', strtotime($date)));

        $today = $date == date('Y-m-d') ? "today" : "";
        if ($date < date('Y-m-d') || $dayname == 'sunday') {
            $tabellaCalendario .= "<td><h4>$currentDay</h4> <button class='btn btn-danger btn-xs' disabled>X</button>";
        } else {
            $tabellaCalendario .= "<td class='$today'><h4>$currentDay</h4> <a href='prenota.php?data=" . $date . "' class='btn btn-success btn-xs'>Prenota</a>";
        }

        $tabellaCalendario .= "</td>";
        // Incremento i contatori
        $currentDay++;
        $dayOfWeek++;
    }

    // Completo la riga dell'ultima settimana

    if ($dayOfWeek != 7) {

        $remainingDays = 7 - $dayOfWeek;
        for ($l = 0; $l < $remainingDays; $l++) {
            $tabellaCalendario .= "<td class='empty'></td>";
        }
    }
    $tabellaCalendario .= "</tr>";
    $tabellaCalendario .= "</table>";
    echo $tabellaCalendario;
}

?>

<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <link rel="stylesheet" href="/style/style.css">

</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php
                $datacomp = getdate();
                if (isset($_GET['mese']) && isset($_GET['anno'])) {
                    $month = $_GET['mese'];
                    $year = $_GET['anno'];
                } else {
                    $month = $datacomp['mon'];
                    $year = $datacomp['year'];
                }
                echo buildTabella($month, $year);
                ?>
            </div>
        </div>
    </div>
</body>

</html>