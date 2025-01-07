<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sklep - Zarządzanie produktami</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php
require_once '../cfg.php';

// Formularz dodawania/edycji produktu
function FormularzProdukt($data = []) {
    $data = array_merge([
        'tytul' => '', 'opis' => '', 'data_wygasniecia' => '', 'cena_netto' => '',
        'podatek_vat' => '', 'ilosc_dostepnych_sztuk' => '', 'status_dostepnosci' => 'dostepny',
        'kategoria' => '', 'gabaryt_produktu' => '', 'zdjecie' => ''
    ], $data);

    return '<form method="POST" enctype="multipart/form-data">
        <label>Tytuł: <input type="text" name="tytul" value="' . htmlspecialchars($data['tytul']) . '" required></label><br>
        <label>Opis: <textarea name="opis" required>' . htmlspecialchars($data['opis']) . '</textarea></label><br>
        <label>Data wygaśnięcia: <input type="datetime-local" name="data_wygasniecia" value="' . $data['data_wygasniecia'] . '"></label><br>
        <label>Cena netto: <input type="number" step="0.01" name="cena_netto" value="' . $data['cena_netto'] . '" required></label><br>
        <label>Podatek VAT (%): <input type="number" name="podatek_vat" value="' . $data['podatek_vat'] . '" required></label><br>
        <label>Ilość dostępnych sztuk: <input type="number" name="ilosc_dostepnych_sztuk" value="' . $data['ilosc_dostepnych_sztuk'] . '" required></label><br>
        <label>Status dostępności: 
            <select name="status_dostepnosci">
                <option value="dostepny"' . ($data['status_dostepnosci'] === 'dostepny' ? ' selected' : '') . '>Dostępny</option>
                <option value="niedostepny"' . ($data['status_dostepnosci'] === 'niedostepny' ? ' selected' : '') . '>Niedostępny</option>
            </select>
        </label><br>
        <label>Kategoria: <input type="text" name="kategoria" value="' . htmlspecialchars($data['kategoria']) . '"></label><br>
        <label>Gabaryt produktu: <input type="text" name="gabaryt_produktu" value="' . htmlspecialchars($data['gabaryt_produktu']) . '"></label><br>
        <label>Zdjęcie: <input type="file" name="zdjecie"></label><br>
        <button type="submit">Zapisz</button>
    </form>';
}

// Dodaj Produkt
function DodajProdukt($data, $file) {
    global $conn;
    $zdjecie = null;
    if (!empty($file['zdjecie']['tmp_name'])) {
        $zdjecie = file_get_contents($file['zdjecie']['tmp_name']);
    }

    $stmt = $conn->prepare("INSERT INTO produkty (tytul, opis, data_utworzenia, data_wygasniecia, cena_netto, podatek_vat, ilosc_dostepnych_sztuk, status_dostepnosci, kategoria, gabaryt_produktu, zdjecie) VALUES (?, ?, NOW(), ?, ?, ?, ?, ?, ?, ?, ?)");

    if ($zdjecie) {
        $stmt->bind_param("sssdiiisss", $data['tytul'], $data['opis'], $data['data_wygasniecia'], $data['cena_netto'], $data['podatek_vat'], $data['ilosc_dostepnych_sztuk'], $data['status_dostepnosci'], $data['kategoria'], $data['gabaryt_produktu']);
        $stmt->send_long_data(10, $zdjecie);
    } else {
        $stmt->bind_param("sssdiiisss", $data['tytul'], $data['opis'], $data['data_wygasniecia'], $data['cena_netto'], $data['podatek_vat'], $data['ilosc_dostepnych_sztuk'], $data['status_dostepnosci'], $data['kategoria'], $data['gabaryt_produktu'], $zdjecie);
    }

    if ($stmt->execute()) {
        header("Location: produkty.php");
        exit;
    } else {
        echo "<p>Błąd podczas dodawania produktu: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// Edytuj Produkt
function EdytujProdukt($id, $data, $file) {
    global $conn;
    $zdjecie = null;
    if (!empty($file['zdjecie']['tmp_name'])) {
        $zdjecie = file_get_contents($file['zdjecie']['tmp_name']);
    }

    $stmt = $conn->prepare("UPDATE produkty SET tytul = ?, opis = ?, data_wygasniecia = ?, cena_netto = ?, podatek_vat = ?, ilosc_dostepnych_sztuk = ?, status_dostepnosci = ?, kategoria = ?, gabaryt_produktu = ?, zdjecie = COALESCE(?, zdjecie) WHERE id = ?");
    $stmt->bind_param("ssdiiisssbi", $data['tytul'], $data['opis'], $data['data_wygasniecia'], $data['cena_netto'], $data['podatek_vat'], $data['ilosc_dostepnych_sztuk'], $data['status_dostepnosci'], $data['kategoria'], $data['gabaryt_produktu'], $zdjecie, $id);

    if ($stmt->execute()) {
        header("Location: produkty.php");
        exit;
    } else {
        echo "<p>Błąd podczas aktualizacji produktu: " . $stmt->error . "</p>";
    }
    $stmt->close();
}



// Pokaż Produkty
function PokazProdukty() {
    global $conn;
    $result = $conn->query("SELECT * FROM produkty ORDER BY id DESC");

    echo '<h2>Lista Produktów</h2>';
    echo '<a href="produkty.php?action=add">Dodaj Produkt</a><br><br>';

    while ($row = $result->fetch_assoc()) {
        echo '<div>';
        echo '<h3>' . htmlspecialchars($row['tytul']) . '</h3>';
        echo '<p>' . htmlspecialchars($row['opis']) . '</p>';
        echo '<p>Cena netto: ' . $row['cena_netto'] . ' zł</p>';
        echo '<p>VAT: ' . $row['podatek_vat'] . '%</p>';
        echo '<p>Stan magazynu: ' . $row['ilosc_dostepnych_sztuk'] . '</p>';
        echo '<a href="produkty.php?action=edit&id=' . $row['id'] . '">Edytuj</a> | <a href="produkty.php?action=delete&id=' . $row['id'] . '">Usuń</a>';
        echo '</div><hr>';
    }
}

// Usuń Produkt
function UsunProdukt($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM produkty WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: produkty.php");
        exit;
    } else {
        echo "<p>Błąd podczas usuwania produktu: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// Akcje
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $id = $_GET['id'] ?? null;

    if ($action === 'add') {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            DodajProdukt($_POST, $_FILES);
        } else {
            echo FormularzProdukt();
        }
    } elseif ($action === 'edit' && $id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            EdytujProdukt($id, $_POST, $_FILES);
        } else {
            global $conn;
            $result = $conn->query("SELECT * FROM produkty WHERE id = $id");
            $product = $result->fetch_assoc();
            echo FormularzProdukt($product);
        }
    } elseif ($action === 'delete' && $id) {
        UsunProdukt($id);
    }
}

// Wyświetla liste
PokazProdukty();
?>
</body>
</html>
