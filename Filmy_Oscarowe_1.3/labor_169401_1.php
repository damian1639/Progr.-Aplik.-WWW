<?php
session_start();
echo 'Include i require_once <br />';
include('include.php'); 
require_once('require.php');

echo '<br />Przykłady warunków if, else, elseif, switch <br />';

$x = 10;
if ($x < 10) {
    echo 'Mniej niż 10 <br />';
} elseif ($x == 10) {
    echo 'Równe 10 <br />';
} else {
    echo 'Więcej niż 10 <br />';
}

switch ($x) {
    case 3:
        echo 'x wynosi 3 <br />';
        break;
    case 10:
        echo 'x wynosi 10 <br />';
        break;
    default:
        echo 'x ma inną wartość <br />';
        break;
}

echo '<br />Przykład pętli while i for <br />';

$i = 0;
while ($i < 3) {
    echo 'Pętla while ' . $i . '<br />';
    $i++;
}

for ($j = 0; $j < 3; $j++) {
    echo 'Pętla for ' . $j . '<br />';
}

echo '<br />Przykład zmiennych $_GET, $_POST, $_SESSION <br />';

if (isset($_GET['wartosc_get'])) {
    echo 'Zmienna $_GET: ' . $_GET['wartosc_get'] . '<br />';
} else {
    echo 'Nie podano zmiennej $_GET <br />';
}

if (isset($_POST['wartosc_post'])) {
    echo 'Zmienna $_POST: ' . $_POST['wartosc_post'] . '<br />';
} else {
    echo 'Nie podano zmiennej $_POST <br />';
}


$_SESSION['name'] = 'Damian';
echo 'Zmienna $_SESSION: ' . $_SESSION['name'] . '<br />';

?>