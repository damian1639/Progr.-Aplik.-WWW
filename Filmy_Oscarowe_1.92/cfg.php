<?php
// Ustawienia połączenia z bazą danych
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$baza = 'moja_strona';
$login = 'admin';
$pass = 'haslo';

// Nawiązywanie połączenia z bazą danych
$conn = mysqli_connect($dbhost, $dbuser, $dbpass);

// Sprawdzenie, czy połączenie zostało nawiązane
if (!$conn) echo '<b>przerwane połączenie</b>';

// Wybór bazy danych
if (!mysqli_select_db($conn, $baza)) echo 'nie wybrano bazy';
?>