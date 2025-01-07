<?php
require_once '../cfg.php';

//Dodawanie kategorii
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $parent_id = isset($_POST['parent']) ? (int)$_POST['parent'] : 0;
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';

    if (!empty($name)) {
        $stmt = $conn->prepare("INSERT INTO shop (matka, nazwa) VALUES (?, ?)");
        if ($stmt) {
            $stmt->bind_param("is", $parent_id, $name);

            if ($stmt->execute()) {
                echo "<p>Kategoria została pomyślnie dodana!</p>";
                header("Location: sklep.php");
                exit;
            } else {
                echo "<p>Błąd podczas dodawania kategorii: " . $stmt->error . "</p>";
            }

            $stmt->close();
        } else {
            echo "<p>Błąd przygotowania zapytania: " . $conn->error . "</p>";
        }
    }

    $conn->close();
}
?>
