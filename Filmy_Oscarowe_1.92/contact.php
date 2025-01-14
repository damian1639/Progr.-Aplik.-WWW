<?php

// Generowanie formularzu kontaktowego
function PokazKontakt($formType = 'Kontakt') {

    $emailField = $formType === 'Kontakt' ? 'email' : 'email_recov';
    $submitValue = $formType === 'Kontakt' ? 'Wyślij' : 'Przypomnij hasło';
    $titleField = $formType === 'Kontakt' ? '<tr><td>Tytuł: </td><td><input type="text" name="title" /></td></tr>' : '';
    $contentField = $formType === 'Kontakt' ? '<tr><td>Zawartość: </td><td><textarea name="content"></textarea></td></tr>' : '';

    // Zwrócenie formularza
    return '
    <div class="form">
        <form method="post" action="' . htmlspecialchars($_SERVER['REQUEST_URI']) . '">
            <table>
                <tr><td>Email: </td><td><input type="text" name="' . $emailField . '" /></td></tr>
                ' . $titleField . '
                ' . $contentField . '
                <tr><td></td><td><input type="submit" value="' . $submitValue . '" /></td></tr>
            </table>
        </form>
    </div>';
}

// Wysyłanie wiadomości kontaktowej
function WyslijMailKontakt($odbiorca) {

    if (empty($_POST['email']) || empty($_POST['title']) || empty($_POST['content'])) {
        echo '[Nie wypełniono wszystkich pól]';
        echo PokazKontakt();
    } else {
        
        $mail = [
            'sender' => htmlspecialchars($_POST['email']),
            'subject' => htmlspecialchars($_POST['title']),
            'body' => htmlspecialchars($_POST['content']),
            'recipient' => $odbiorca
        ];

        // Tworzenie nagłówków wiadomości
        $header  = "From: Formularz kontaktowy <" . $mail['sender'] . ">\n";
        $header .= "MIME-Version: 1.0\nContent-Type: text/plain; charset=utf-8\n";
        $header .= "X-Mailer: PHP/" . phpversion();

        // Wysyłanie wiadomości 
        if (mail($mail['recipient'], $mail['subject'], $mail['body'], $header)) {
            echo '[Wiadomość została wysłana pomyślnie]';
        } else {
            echo '[Błąd podczas wysyłania wiadomości]';
        }
    }
}   

// Wysyłanie wiadomości z przypomnieniem hasła
function PrzypomnijHaslo($odbiorca) {

    if (empty($_POST['email_recov'])) {
        echo '[Nie wypełniono pola]';
        echo PokazKontakt('PrzypomnijHaslo');
    } else {
        
        $mail = [
            'sender' => htmlspecialchars($_POST['email_recov']),
            'subject' => "Odzyskiwanie hasła",
            'body' => "Twoje hasło: Admin",
            'recipient' => $odbiorca
        ];

        // Tworzenie nagłówków wiadomości
        $header  = "From: Formularz kontaktowy <" . $mail['sender'] . ">\n";
        $header .= "MIME-Version: 1.0\nContent-Type: text/plain; charset=utf-8\n";
        $header .= "X-Mailer: PHP/" . phpversion();

        // Wysyłanie wiadomości i informowanie o wyniku
        if (mail($mail['recipient'], $mail['subject'], $mail['body'], $header)) {
            echo '[Hasło zostało wysłane na podany adres.]';
        } else {
            echo '[Błąd podczas wysyłania hasła.]';
        }
    }
}
?>
