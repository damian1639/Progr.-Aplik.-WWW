<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sklep - Zarządzanie kategoriami</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php
require_once '../cfg.php';

// Formularz dodawania kategorii
function pokazFormularzDodawania() {
    return '
        <h2>Dodaj Nową Kategorię</h2>
        <form method="POST" action="dodajkategorie.php">
            <label for="parent">Kategoria nadrzędna (ID):</label>
            <input type="number" id="parent" name="parent" value="0" required><br><br>
            <label for="name">Nazwa kategorii:</label>
            <input type="text" id="name" name="name" required><br><br>
            <button type="submit">Dodaj</button>
        </form>';
}

// Usuwanie kategorii
function usunKategorie($id) {
    global $conn;

    $stmt = $conn->prepare("DELETE FROM shop WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<p>Kategoria została usunięta.</p>";
    } else {
        echo "<p>Nie udało się usunąć kategorii: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// Wyświetalnie kategorii
function pokazKategorie() {
    global $conn;

    $stmt = $conn->query("SELECT id, matka AS parent_id, nazwa AS name FROM shop ORDER BY matka, id");

    $categories = [];
    while ($row = $stmt->fetch_assoc()) {
        $categories[] = $row;
    }

    $output = '<h2>Lista Kategorii</h2>';
    
    if (empty($categories)) {
        $output .= '<p>Brak kategorii do wyświetlenia.</p>';
    } else {
        $output .= '<ul>';

        foreach ($categories as $cat) {
            if ($cat['parent_id'] == 0) { 
                $output .= '<li>' . htmlspecialchars($cat['name']) . 
                           ' [ID: ' . $cat['id'] . '] ' . 
                           '<a href="?action=edit&id=' . $cat['id'] . '">Edytuj</a> | ' . 
                           '<a href="?action=delete&id=' . $cat['id'] . '">Usuń</a>';

                $output .= '<ul>';
                foreach ($categories as $subcat) {
                    if ($subcat['parent_id'] == $cat['id']) { 
                        $output .= '<li>' . htmlspecialchars($subcat['name']) . 
                                   ' [ID: ' . $subcat['id'] . '] ' . 
                                   '<a href="?action=edit&id=' . $subcat['id'] . '">Edytuj</a> | ' . 
                                   '<a href="?action=delete&id=' . $subcat['id'] . '">Usuń</a></li>';
                    }
                }
                $output .= '</ul>'; 
                $output .= '</li>'; 
            }
        }

        $output .= '</ul>';
    }

    return $output;
}


// Edycja kategorii
function EdytujKategorie() {
    global $conn;

    if (isset($_GET['id'])) {
        $id = (int)$_GET['id']; 
    } else {
        echo "<p>Nie podano ID kategorii.</p>";
        exit;
    }

    $stmt = $conn->prepare("SELECT matka, nazwa FROM shop WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $matka = $row['matka'];
        $nazwa = $row['nazwa'];

        // Formularz edycji kategorii
        $form = '<h3>Edycja Kategorii o ID: ' . $id . '</h3>';
        $form .= '<form method="POST" action="zapiszKategorie.php?id=' . $id . '">';
        $form .= '<label for="parent">Kategoria nadrzędna (ID):</label>';
        $form .= '<input type="number" id="parent" name="parent" value="' . $matka . '" required><br><br>';
        $form .= '<label for="name">Nazwa kategorii:</label>';
        $form .= '<input type="text" id="name" name="name" value="' . htmlspecialchars($nazwa) . '" required><br><br>';
        $form .= '<button type="submit">Zapisz zmiany</button>';
        $form .= '</form>';

        return $form;
    } else {
        echo "<p>Kategoria o ID: $id nie istnieje.</p>";
        exit;
    }

    $stmt->close();
}

// Przycisk dodaj kategorie
echo '<a href="?action=add" class="btn">Dodaj Kategorię</a>';
echo '<hr>';

// Obsługa akcji
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $id = $_GET['id'] ?? null; 

    if ($action === 'delete' && $id) {
        usunKategorie($id);
    } elseif ($action === 'add') {
        echo pokazFormularzDodawania();
        exit; 
    } elseif ($action === 'edit' && $id) {
        echo EdytujKategorie();
        exit;  
    }
}

echo pokazKategorie();
echo '<a href="produkty.php">Zarządzaj produktami</a>';

?>
</body>
</html>
