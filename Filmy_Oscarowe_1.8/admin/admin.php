<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php
// Rozpoczęcie sesji dla logowania
session_start();

// Dołączenie pliku konfiguracyjnego
require('../cfg.php');

// Renderowanie głównego kontenera dla strony administratora
echo "<div class='main'>";

// Formularz logowania
function FormularzLogowania() {
    $wynik = '
    <div class="logowanie">
        <h1 class="heading">Panel CMS:</h1>
        <a href="../index.php"> Strona główna </a><br>
        <form class="formularz_logowania" method="POST" name="LoginForm" enctype="multipart/form-data" action="' . $_SERVER['REQUEST_URI'] . '">
            <table class="logowanie">
                <tr><td class="log4_t">[login]</td><td><input type="text" name="login_email" class="logowanie"/></td></tr>
                <tr><td class="log4_t">[haslo]</td><td><input type="password" name="login_pass" class="logowanie"/></td></tr>
                <tr><td><br/></td><td><input type="submit" name="xl_submit" class="logowanie" value="Zaloguj"/></td></tr>
            </table>
        </form>
    </div>
    ';
    return $wynik;
}

// Wyświetlenie podstron w panelu administracyjnym
function ListaPodstron() {
    global $conn;

    $wynik = '<h3>Podstrony:</h3>';
    $wynik .= '<a href="' . $_SERVER['PHP_SELF'] . '?action=dodaj">Dodaj podstronę</a> <br /><br />';
    $wynik .= '<table class="tabela_akcji">';
    $wynik .= '<tr><th>ID</th><th>Tytuł podstrony</th><th>Akcje</th></tr>';

    // Pobranie listy podstron z bazy danych
    $query = "SELECT id, page_title FROM page_list";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Iteracja przez wyniki zapytania i tworzenie tabeli podstron
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $page_title = $row['page_title'];

            $wynik .= '<tr>';
            $wynik .= '<td>' . $id . '</td>';
            $wynik .= '<td>' . $page_title . '</td>';
            $wynik .= '<td>
                        <a href="' . $_SERVER['PHP_SELF'] . '?action=edytuj&id=' . $id . '">Edytuj</a> | 
                        <a href="' . $_SERVER['PHP_SELF'] . '?action=usun&id=' . $id . '">Usuń</a>
                       </td>';
            $wynik .= '</tr>';
        }
    } else {
        $wynik .= '<tr><td colspan="3">Brak podstron do wyświetlenia.</td></tr>';
    }

    $wynik .= '</table>';
    echo $wynik; // Wyświetlenie tabeli z podstronami

    // Obsługa akcji administracyjnych
    if (isset($_GET['action'])) {
        if ($_GET['action'] === 'edytuj' && isset($_GET['id'])) {
            echo EdytujPodstrone();
        } elseif ($_GET['action'] === 'usun' && isset($_GET['id'])) {
            echo UsunPodstrone();
        } elseif ($_GET['action'] === 'dodaj') {
            echo DodajNowaPodstrone();
        }
    }
}

// Edycja podstrony
function EdytujPodstrone() {
    global $conn;

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    } else {
        echo "Brak podstrony z tym ID";
        exit;
    }

    // Pobranie danych podstrony do edycji
    $query = "SELECT page_title, page_content, status FROM page_list WHERE id = '$id'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        $page_title = $row['page_title'];
        $page_content = $row['page_content'];
        $page_is_active = $row['status'];

        // Generowanie formularza edycji
        $wynik = '<h3>Edycja Podstrony o ID: ' . $id . '</h3>';
        $wynik .= '<form method="POST" action="zapisywanie.php?id=' . $id . '">';
        $wynik .= 'Tytuł: <input class="tytul" type="text" name="page_title" value="' . $page_title . '"><br /><br />';
        $wynik .= 'Treść: <textarea class="tresc" rows="20" cols="100" name="page_content">' . $page_content . '</textarea><br /><br />';
        $wynik .= 'Podstrona aktywna: <input class="aktywna" type="checkbox" name="page_is_active" value="1"' . ($page_is_active == 1 ? ' checked="checked"' : '') . '><br /><br />';
        $wynik .= '<input class="zapisz" type="submit" name="zapisz" value="Zapisz">';
        $wynik .= '</form>';

        return $wynik;
    }
}

// Dodawanie nowej podstrony
function DodajNowaPodstrone() {
    $wynik = '<h3>Dodaj podstronę:</h3>';
    $wynik .= '<form method="POST" action="dodajpodstrone.php">';
    $wynik .= 'Tytuł: <input class="tytul" type="text" name="page_title" value=""><br /><br />';
    $wynik .= 'Treść: <textarea class="tresc" rows="20" cols="100" name="page_content"></textarea><br /><br />';
    $wynik .= 'Podstrona aktywna: <input class="aktywna" type="checkbox" name="page_is_active" value="1"><br /><br />';
    $wynik .= '<input class="zapisz" type="submit" value="Dodaj">';
    $wynik .= '</form>';

    return $wynik;
}

// Usuwanie podstrony
function UsunPodstrone() {
    global $conn;

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    } else {
        echo "Brak podstrony z tym ID";
        exit;
    }

    $query = "DELETE FROM page_list WHERE id = $id LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "Usunięto podstronę";
        header("Location: admin.php");
    } else {
        echo "Błąd usuwania podstrony";
        exit;
    }
}

// Logowanie administratora
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    echo '<h1>Administrator</h1>';
    echo ListaPodstron(); // Wyświetlenie listy podstron
    echo '<a href="wylogowanie.php">Wyloguj</a><br><br>';
} else {
    echo FormularzLogowania(); // Wyświetlenie formularza logowania
}

// Obsługa danych przesłanych z formularza logowania
if (isset($_POST['login_email']) && isset($_POST['login_pass'])) {
    $formLogin = $_POST['login_email'];
    $formPass = $_POST['login_pass'];

    if ($formLogin === $login && $formPass === $pass) {
        $_SESSION['admin_logged_in'] = true;
        header("Refresh:0");
    } else {
        echo 'Nieprawidłowy login lub hasło, spróbuj ponownie.';
    }
}

echo "</div>";
?>
</body>
</html>
