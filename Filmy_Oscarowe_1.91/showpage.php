<?php

// Dołączenie pliku konfiguracyjnego
require('cfg.php');

// Pobieranie i wyświetlanie treści podstrony na podstawie ID
function PokazPodstrone($id) {
    global $conn;

    $id_clear = htmlspecialchars($id);

    $query = "SELECT * FROM page_list WHERE id='$id_clear' LIMIT 1";
    $result = mysqli_query($conn, $query);

    $row = mysqli_fetch_array($result);

    // Sprawdzenie, czy strona została znaleziona
    if (empty($row['id'])) {
        $web = '[nie_znaleziono_strony]'; 
    } else {
        $web = $row['page_content'];
    }

    return $web;
}
?>
