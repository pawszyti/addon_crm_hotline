<?php
session_start();
//HISTORIA LOGOWAŃ
if(isset($_SESSION['hotline']) && $_SESSION['hotline'] == sha1(admin) && isset($_COOKIE['admin']))
{
require_once ('../config/config.php');
$username = $_SESSION['username'];
$name = $_SESSION['name'];
$surname = $_SESSION['surname'];
$limit = 0;
setcookie("admin", 'online', time() + 1800); //czas życia cookie

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="author" content="Paweł Szymczyk" />
    <title>Panel Hotline</title>
    <link href="../css/style.css" rel="stylesheet">
    <link href="../css/bootstrap.css" rel="stylesheet">

    <script src="../js/jquery-3.1.1.js"></script>
    <script src="../js/bootstrap.js"></script>
</head>
<body>

<div class="navbar navbar-default btn-variants navbar-fixed" role="navigation">
    <div style="text-align: center" class="col-lg-8 col-lg-offset-2 col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3">
        <h2 class="font_logo ">ADMINISTRATOR HOTLINE</b></h2>
    </div>
    <div class="col-lg-1 col-lg-offset-1 col-md-2 col-md-offset-1 col-sm-2 col-sm-offset-1 col-sm-6 col-sm-offset-3 col-xs-4 col-xs-offset-4" ><a data-toggle="tooltip" data-placement="bottom" title="Zalogowany:<?php  echo "    ".$name." ".$surname; ?>" role="button" class="btn btn-default btn-sm " style="margin-top: 15px" href="../logout.php">Wyloguj</a></div>
</div>

<div class="container">
    <div style="margin-bottom: 60px">
        <a href="../admin.php" class="btn btn-success col-lg-2 col-lg-offset-3">Użytkownicy</a>
        <a href="hotline_historia.php" class="btn btn-danger col-lg-2" style="margin-left: 6px;margin-right: 6px" >Historia akcji</a>
        <a class="btn btn-info col-lg-2" disabled  >Historia logowań</a>

    </div>



    <table class="table table-striped" cellspacing='0' style='text-align: center'>
        <tr>
            <th style="text-align: center">lp.</th>
            <th style="text-align: center">Data</th>
            <th style="text-align: center">Użytkownik</th>
            <th style="text-align: center">IP</th>


        </tr>
        <?php
        //wyświetl historie logowań z 1.13
        $zapytanie_historia = "SELECT * FROM hotline_logowania ORDER BY id_logowanie DESC";
        $wynik_historia = $db13->query($zapytanie_historia);
        $ilosc_historia = $wynik_historia->num_rows;
        $licznik=0;
        for ($i = 0; $i < $ilosc_historia; $i++){?>
            <?php $tablica_historia = $wynik_historia->fetch_assoc(); $licznik++;?>

            <tr>
                <td><?php echo $licznik?></td>
                <td><?php echo $tablica_historia[data_logowanie]?></td>

                <?php
                //sprawdzanie jaki user/admin się pod ID z 1.13
                $zapytanie_historia_admin = "SELECT * FROM hotline_users WHERE id_crm LIKE $tablica_historia[user_logowanie] ";
                $wynik_historia_admin = $db13->query($zapytanie_historia_admin);
                $tablica_historia_admin = $wynik_historia_admin->fetch_assoc();
                $nazwa = $tablica_historia_admin[imie]." ".$tablica_historia_admin[nazwisko];
                ?>

                <td><?php echo $nazwa; ?></td>
                <td><?php echo $tablica_historia[ip_logowanie]?></td>


            </tr>

            <?php
        }
        echo "<div>".$_SESSION['alert']."</div>";
        unset($_SESSION['alert']);
        }
        else
        {
            header('location: ../logout.php');
            exit();
            //jesli pierwszy warunek nie został spełniony to prześlij to strony wylogowania
        }
        //LOGOWANIE - SPRAWDZENIE - STOP
        ?>


        <?php
        $db2->close();
        $db13->close();
        $db2_hr->close();
        $db2_capital->close();
        ?>

</body>
</html>