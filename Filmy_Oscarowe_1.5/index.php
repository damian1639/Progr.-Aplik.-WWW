<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oscarowe Filmy</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/kolorujtlo.js" type="text/javascript"></script>
    <script src="js/timedate.js" type="text/javascript"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="css/style.css" />
</head>
<body onload="startclock()">
<header><h1>Oscarowe Filmy</h1></header>
    <center>
        <div id="zegarek"></div>
        <div id="data"></div>
    </center>
    <nav>
        <ul>
        <li><a href="index.php?idp=1">Strona główna</a></li>
            <li><a href="index.php?idp=2">Lot nad kukułczym gniazdem</a></li>
            <li><a href="index.php?idp=3">Milczenie owiec</a></li>
            <li><a href="index.php?idp=4">Forrest Gump</a></li>
            <li><a href="index.php?idp=5">Gladiator</a></li>
            <li><a href="index.php?idp=6">Oppenheimer</a></li>
            <li><a href="index.php?idp=8">Filmy</a></li>
            <li><a href="index.php?idp=7">Kontakt</a></li>
        </ul>
    </nav>

    <?php

    include('cfg.php');
    include('showpage.php');
    
    if (empty($_GET['idp'])) {
        $strona_id = 1;
    } else {
        $strona_id = $_GET['idp'];
    }
    
    $tresc_strony = PokazPodstrone($strona_id);
    
    if ($tresc_strony === '[nie_znaleziono_strony]') {
        echo 'Strona nie istnieje';
    } else {
        echo $tresc_strony;
    }
    $nr_indeksu = '169401'; 
    $nrGrupy = '1';         

    echo 'Autor: Damian Biegajlo '.$nr_indeksu.' grupa '.$nrGrupy.'<br /><br />';
?>

</body>
</html>
