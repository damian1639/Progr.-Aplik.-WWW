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
        <li><a href="index.php">Strona główna</a></li>
            <li><a href="index.php?idp=lot_nad_kukulczym_gniazdem">Lot nad kukułczym gniazdem</a></li>
            <li><a href="index.php?idp=milczenie_owiec">Milczenie owiec</a></li>
            <li><a href="index.php?idp=forrest_gump">Forrest Gump</a></li>
            <li><a href="index.php?idp=gladiator">Gladiator</a></li>
            <li><a href="index.php?idp=oppenheimer">Oppenheimer</a></li>
            <li><a href="index.php?idp=filmy">Filmy</a></li>
            <li><a href="index.php?idp=kontakt">Kontakt</a></li>
        </ul>
    </nav>

    <?php
      error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

      if (empty($_GET['idp'])) {
          $strona = './html/glowna.html';
      } 
      elseif ($_GET['idp'] == 'lot_nad_kukulczym_gniazdem') {
          $strona = './html/lot_nad_kukulczym_gniazdem.html';
      } 
      elseif ($_GET['idp'] == 'milczenie_owiec') {
          $strona = './html/milczenie_owiec.html';
      } 
      elseif ($_GET['idp'] == 'forrest_gump') {
          $strona = './html/forrest_gump.html';
      }
      elseif ($_GET['idp'] == 'gladiator') {
              $strona = './html/gladiator.html';
      }
      elseif ($_GET['idp'] == 'oppenheimer') {
          $strona = './html/oppenheimer.html';
      }
      elseif ($_GET['idp'] == 'kontakt') {
          $strona = './html/kontakt.html';
      }
      elseif ($_GET['idp'] == 'filmy') {
          $strona = './html/filmy.html';
      }
      else {
          $strona = './html/glowna.html';
      }
  
      if (file_exists($strona)) {
          include($strona);
      } else {
          echo 'Strony brak';
      }

    $nr_indeksu = '169401'; 
    $nrGrupy = '1';         

    echo 'Autor: Damian Biegajlo '.$nr_indeksu.' grupa '.$nrGrupy.'<br /><br />';
?>

</body>
</html>
