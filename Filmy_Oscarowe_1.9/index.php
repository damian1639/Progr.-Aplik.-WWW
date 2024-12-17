<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oscarowe Filmy - v1.8</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/kolorujtlo.js" type="text/javascript"></script>
    <script src="js/timedate.js" type="text/javascript"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body onload="startclock()">
<header>
    <h1>Oscarowe Filmy</h1>
</header>

<center>
    <!-- Wyświetlanie zegarka i daty -->
    <div id="zegarek"></div>
    <div id="data"></div>
</center>

<nav>
    <!-- Nawigacja po stronie -->
    <ul>
        <li><a href="index.php?idp=1">Strona główna</a></li>
        <li><a href="index.php?idp=2">Lot nad kukułczym gniazdem</a></li>
        <li><a href="index.php?idp=3">Milczenie owiec</a></li>
        <li><a href="index.php?idp=4">Forrest Gump</a></li>
        <li><a href="index.php?idp=5">Gladiator</a></li>
        <li><a href="index.php?idp=6">Oppenheimer</a></li>
        <li><a href="index.php?idp=8">Filmy</a></li>
        <li><a href="index.php?idp=7">Kontakt</a></li>
    </ul>
</nav>

<?php
// Wczytanie plików konfiguracyjnych
include_once('cfg.php');
include_once('showpage.php');
include_once('contact.php');

// Zabezpieczenie zmiennej idp przed code injection
$strona_id = filter_input(INPUT_GET, 'idp', FILTER_VALIDATE_INT) ?: 1;

if ($strona_id === 7) {
    echo "<h1>Kontakt</h1>";
    echo PokazKontakt();

    // Obsługa formularza kontaktowego
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['x1_submit'])) {
        WyslijMailKontakt("169401@student.uwm.edu.pl");
    }

    echo "<br><a href='index.php?idp=7&reset=1'>Odzyskanie hasła</a>";

    // Obsługa odzyskiwania hasła
    if (filter_input(INPUT_GET, 'reset', FILTER_VALIDATE_BOOLEAN)) {
        echo "<h1>Odzyskanie Hasła</h1>";
        echo PokazKontakt('PrzypomnijHaslo');

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['email_recov'])) {
            PrzypomnijHaslo("169401@student.uwm.edu.pl");
        }
    }
} else {
    // Wyświetlenie treści podstrony
    $tresc_strony = PokazPodstrone($strona_id);

    if ($tresc_strony === '[nie_znaleziono_strony]') {
        echo "<h2>Błąd 404: Strona nie istnieje</h2>";
    } else {
        echo $tresc_strony;
    }
}

// O autorze i przycisk do panelu CMS
$nr_indeksu = '169401';
$nrGrupy = '1';
echo "<footer>";
echo 'Autor: Damian Biegajlo '.$nr_indeksu.' grupa '.$nrGrupy;
echo '<a href="admin/admin.php" class="button" style="float: right;">Administrator</a>';
echo "</footer>";
?>

</body>
</html>
