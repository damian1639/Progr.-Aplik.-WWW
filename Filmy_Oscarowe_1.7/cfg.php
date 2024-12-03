<?php

$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$baza = 'moja_strona';
$login = 'admin';
$pass = 'haslo';

$conn = mysqli_connect($dbhost, $dbuser, $dbpass);

if (!$conn) echo '<b>przerwane połączenie</b>';

if (!mysqli_select_db($conn, $baza)) echo 'nie wybrano bazy';
?>