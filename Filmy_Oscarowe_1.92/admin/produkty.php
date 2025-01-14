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
require_once 'koszyk.php';

// Formularz dodawania produktu
function FormularzProdukt($data = []) {
    $data = array_merge([
        'tytul' => '', 'opis' => '', 'data_wygasniecia' => '', 'cena_netto' => '',
        'podatek_vat' => '', 'ilosc_dostepnych_sztuk' => '', 'status_dostepnosci' => 'dostepny',
        'kategoria' => '', 'gabaryt_produktu' => '', 'zdjecie' => ''
    ], $data);

    return '<form method="POST" enctype="multipart/form-data">
    <label>Tytuł: <input type="text" name="tytul" value="' . htmlspecialchars($data['tytul']) . '" required></label><br>
    <label>Opis: <textarea name="opis" required>' . htmlspecialchars($data['opis']) . '</textarea></label><br>
    <label>Cena netto: <input type="number" step="0.01" name="cena_netto" value="' . htmlspecialchars($data['cena_netto']) . '" required></label><br>
    <label>Podatek VAT (%): <input type="number" name="podatek_vat" value="' . htmlspecialchars($data['podatek_vat']) . '" required></label><br>
    <label>Ilość dostępnych sztuk: <input type="number" name="ilosc_dostepnych_sztuk" value="' . htmlspecialchars($data['ilosc_dostepnych_sztuk']) . '" required></label><br>
    <label>Status dostępności: 
        <select name="status_dostepnosci">
            <option value="dostepny"' . ($data['status_dostepnosci'] == 'dostepny' ? ' selected' : '') . '>Dostępny</option>
            <option value="niedostepny"' . ($data['status_dostepnosci'] == 'niedostepny' ? ' selected' : '') . '>Niedostępny</option>
        </select>
    </label><br>
    <label>Data wygaśnięcia: <input type="date" name="data_wygasniecia" value="' . htmlspecialchars($data['data_wygasniecia']) . '"></label><br>
    <label>Kategoria: <input type="text" name="kategoria" value="' . htmlspecialchars($data['kategoria']) . '"></label><br>
    <label>Gabaryt: <input type="text" name="gabaryt_produktu" value="' . htmlspecialchars($data['gabaryt_produktu']) . '"></label><br>
    <button type="submit">Zapisz</button>
</form>';
}

function DodajProdukt($data) {
    global $conn;

    $data['data_wygasniecia'] = !empty($data['data_wygasniecia']) ? $data['data_wygasniecia'] : null;
    $data['kategoria'] = !empty($data['kategoria']) ? $data['kategoria'] : null;
    $data['gabaryt_produktu'] = !empty($data['gabaryt_produktu']) ? $data['gabaryt_produktu'] : null;

    $stmt = $conn->prepare("INSERT INTO produkty (tytul, opis, data_utworzenia, data_wygasniecia, cena_netto, podatek_vat, ilosc_dostepnych_sztuk, status_dostepnosci, kategoria, gabaryt_produktu) VALUES (?, ?, NOW(), ?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        die('Błąd przygotowania zapytania: ' . $conn->error);
    }

    $stmt->bind_param("ssdiiisss", 
        $data['tytul'], 
        $data['opis'], 
        $data['data_wygasniecia'], 
        $data['cena_netto'], 
        $data['podatek_vat'], 
        $data['ilosc_dostepnych_sztuk'], 
        $data['status_dostepnosci'], 
        $data['kategoria'], 
        $data['gabaryt_produktu']
    );

    if ($stmt->execute()) {
        header("Location: produkty.php");
        exit;
    } else {
        echo "<p>Błąd podczas dodawania produktu: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// Usuwanie produktu
function UsunProdukt($id) {
    global $conn;

    $stmt = $conn->prepare("DELETE FROM produkty WHERE id = ?");

    if (!$stmt) {
        die('Błąd przygotowania zapytania: ' . $conn->error);
    }

    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<p>Produkt został usunięty.</p>";
    } else {
        echo "<p>Błąd podczas usuwania produktu: " . $stmt->error . "</p>";
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
        echo '<form method="POST" action="produkty.php?action=addToCart&id=' . $row['id'] . '">';
        echo '<input type="hidden" name="tytul" value="' . htmlspecialchars($row['tytul']) . '">';
        echo '<input type="hidden" name="cena_netto" value="' . $row['cena_netto'] . '">';
        echo '<input type="hidden" name="podatek_vat" value="' . $row['podatek_vat'] . '">';
        echo '<label for="ilosc">Ilość:</label>';
        echo '<input type="number" name="ilosc" value="1" min="1" required>';
        echo '<button type="submit">Dodaj do koszyka</button>';
        echo '</form>';
        echo '<a href="produkty.php?action=edit&id=' . $row['id'] . '">Edytuj</a> | ';
        echo '<a href="produkty.php?action=delete&id=' . $row['id'] . '">Usuń</a>';
        echo '</div><hr>';
    }
}

// Akcje
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action === 'addToCart' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = (int)$_GET['id'];
        $title = $_POST['tytul'];
        $quantity = (int)$_POST['ilosc'];
        $price = (float)$_POST['cena_netto'];
        $vat = (float)$_POST['podatek_vat'];

        addToCart($id, $title, $quantity, $price, $vat);

        echo "<p>Produkt został dodany do koszyka.</p>";
    }
    elseif ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'tytul' => $_POST['tytul'],
            'opis' => $_POST['opis'],
            'data_wygasniecia' => $_POST['data_wygasniecia'],
            'cena_netto' => $_POST['cena_netto'],
            'podatek_vat' => $_POST['podatek_vat'],
            'ilosc_dostepnych_sztuk' => $_POST['ilosc_dostepnych_sztuk'],
            'status_dostepnosci' => $_POST['status_dostepnosci'],
            'kategoria' => $_POST['kategoria'],
            'gabaryt_produktu' => $_POST['gabaryt_produktu']
        ];
        DodajProdukt($data);
    }
    elseif ($action === 'delete' && isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        UsunProdukt($id);
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'add') {
    echo FormularzProdukt();
} elseif (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $result = $conn->query("SELECT * FROM produkty WHERE id = $id");
    if ($row = $result->fetch_assoc()) {
        echo FormularzProdukt($row);
    }
}

PokazProdukty();

echo '<h2>Koszyk</h2>';
showCart();
echo '<a href="produkty.php?action=clear">Wyczyść koszyk</a>';
?>


</body>
</html>
