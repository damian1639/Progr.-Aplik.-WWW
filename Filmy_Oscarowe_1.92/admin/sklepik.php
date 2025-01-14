<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sklep - Przegląd produktów</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<?php
require_once '../cfg.php';
require_once 'koszyczek.php';

function PokazProdukty() {
    global $conn;
    $result = $conn->query("SELECT * FROM produkty ORDER BY id DESC");

    echo '<h2>Lista Produktów</h2>';

    while ($row = $result->fetch_assoc()) {
        echo '<div>';
        echo '<h3>' . htmlspecialchars($row['tytul']) . '</h3>';
        echo '<p>' . htmlspecialchars($row['opis']) . '</p>';
        echo '<p>Cena netto: ' . $row['cena_netto'] . ' zł</p>';
        echo '<p>VAT: ' . $row['podatek_vat'] . '%</p>';
        echo '<p>Stan magazynu: ' . $row['ilosc_dostepnych_sztuk'] . '</p>';
        echo '<form class="add-to-cart-form" data-product-id="' . $row['id'] . '" data-product-title="' . htmlspecialchars($row['tytul']) . '" data-product-price="' . $row['cena_netto'] . '" data-product-vat="' . $row['podatek_vat'] . '">';
        echo '<label for="ilosc">Ilość:</label>';
        echo '<input type="number" name="ilosc" value="1" min="1" required>';
        echo '<button type="submit">Dodaj do koszyka</button>';
        echo '</form>';
        echo '</div><hr>';
    }
}

//Dodawanie do koszyka
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
}

PokazProdukty();

echo '<h2>Koszyk</h2>';
showCart();
?>

<script>
$(document).on('submit', '.add-to-cart-form', function(event) {
    event.preventDefault();

    var form = $(this);
    var productId = form.data('product-id');
    var productTitle = form.data('product-title');
    var productPrice = form.data('product-price');
    var productVat = form.data('product-vat');
    var quantity = form.find('input[name="ilosc"]').val();

    $.ajax({
        url: 'koszyczek.php',
        type: 'POST',
        data: {
            action: 'addToCart',
            id: productId,
            tytul: productTitle,
            cena_netto: productPrice,
            podatek_vat: productVat,
            ilosc: quantity
        },
        success: function(response) {
            location.reload();
        },
        error: function() {
            alert('Wystąpił błąd podczas dodawania produktu do koszyka.');
        }
    });
});
</script>

</body>
</html>
