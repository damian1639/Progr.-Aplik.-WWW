<?php
//zapisywanie edytowanych
require('../cfg.php');

if (isset($_POST['zapisz'])) {
    if (isset($_POST['page_title']) && isset($_POST['page_content'])) {
     $page_title = $_POST['page_title'];
     $page_content = $_POST['page_content'];
     $page_is_active = isset($_POST['page_is_active']) ? 1 : 0;
     $id = $_GET['id'];

     $query = "UPDATE page_list SET page_title = '$page_title', page_content = '$page_content', status = '$page_is_active' WHERE id = '$id' LIMIT 1";
     $result = mysqli_query($conn, $query);
     if ($result) {
         echo "Zaktualizowano podstronę";
         header("Location: ./admin.php");
     } else {
         echo "Błąd";
     }
 }
}
else {
    echo "Dane nie przesłane";
}
?>
