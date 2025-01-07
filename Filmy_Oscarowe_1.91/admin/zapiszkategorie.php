<?php
require_once '../cfg.php';

//Zapisywanie kategorii
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id'])) {
    $id = (int)$_GET['id']; 
    $parent_id = isset($_POST['parent']) ? (int)$_POST['parent'] : 0;
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';

    if (!empty($name)) {
        $stmt = $conn->prepare("UPDATE shop SET matka = ?, nazwa = ? WHERE id = ?");
        $stmt->bind_param("isi", $parent_id, $name, $id); 

        if ($stmt->execute()) {
            header("Location: sklep.php");
            exit;
        } else {
            echo "<p>Błąd podczas aktualizacji kategorii: " . $stmt->error . "</p>";
        }

        $stmt->close();
    }
}

$conn->close();
?>
