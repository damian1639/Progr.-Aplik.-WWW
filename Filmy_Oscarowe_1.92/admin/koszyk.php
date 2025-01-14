<?php
session_start();

if (!isset($_SESSION['koszyk'])) {
    $_SESSION['koszyk'] = [];
}

// Dodanie produktu do koszyka
function addToCart($id, $title, $quantity, $price, $vat) {
    if ($quantity <= 0 || $price < 0 || $vat < 0) {
        echo "<p>Błędne dane produktu.</p>";
        return;
    }

    $product = [
        'id' => $id,
        'title' => $title,
        'quantity' => $quantity,
        'price' => $price,
        'vat' => $vat
    ];

    if (isset($_SESSION['koszyk'][$id])) {
        $_SESSION['koszyk'][$id]['quantity'] += $quantity;
    } else {
        $_SESSION['koszyk'][$id] = $product;
    }
}

// Wyświetlenie zawartości koszyka
function showCart() {
    if (empty($_SESSION['koszyk'])) {
        echo "<p>Koszyk jest pusty.</p>";
        return;
    }

    $total = 0;
    echo '<table border="1">';
    echo '<tr><th>Produkt</th><th>Ilość</th><th>Cena netto</th><th>VAT</th><th>Cena brutto</th><th>Usuń</th></tr>';

    foreach ($_SESSION['koszyk'] as $product) {
        $price_brutto = $product['price'] + ($product['price'] * $product['vat'] / 100);
        $total += $price_brutto * $product['quantity'];

        echo '<tr>';
        echo '<td>' . htmlspecialchars($product['title']) . '</td>';
        echo '<td>' . $product['quantity'] . '</td>';
        echo '<td>' . $product['price'] . ' zł</td>';
        echo '<td>' . $product['vat'] . '%</td>';
        echo '<td>' . $price_brutto . ' zł</td>';
        echo '<td><a href="?action=remove&id=' . $product['id'] . '">Usuń</a></td>';
        echo '</tr>';
    }

    echo '</table>';
    echo '<p>Łączna wartość: ' . $total . ' zł</p>';
}

// Usunięcie produktu z koszyka
function removeFromCart($id) {
    if (isset($_SESSION['koszyk'][$id])) {
        unset($_SESSION['koszyk'][$id]);
    }
}
if (isset($_GET['action']) && $_GET['action'] === 'remove' && isset($_GET['id'])) {
    $id = $_GET['id'];
    removeFromCart($id); 
    header('Location: ' . $_SERVER['PHP_SELF']); 
    exit;
}
?>
