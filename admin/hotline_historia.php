<?php
session_start();
//HISTORIA WYKONANY AKCJI

//czy zalogowany jako admin
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
    <a class="btn btn-danger col-lg-2" style="margin-left: 6px;margin-right: 6px" disabled >Historia akcji</a>
    <a href="hotline_logowanie.php" class="btn btn-info col-lg-2" >Historia logowań</a>

</div>



    <table class="table table-striped" cellspacing='0' style='text-align: center'>
        <tr>
            <th style="text-align: center">lp.</th>
            <th style="text-align: center">Data</th>
            <th style="text-align: center">Administrator</th>
            <th style="text-align: center">Użytkownik</th>
            <th style="text-align: center">Oddział</th>
            <th style="text-align: center">Akcja</th>

        </tr>
        <?php
        //wyświetl historie z 1.13 posortowaną po dacie
        $zapytanie_historia = "SELECT * FROM hotline_historia, hotline_akcja WHERE hotline_historia.id_akcja = hotline_akcja.id ORDER BY data DESC ";
        $wynik_historia = $db13->query($zapytanie_historia);
        $ilosc_historia = $wynik_historia->num_rows;
        $licznik=0;
        for ($i = 0; $i < $ilosc_historia; $i++){?>
            <?php $tablica_historia = $wynik_historia->fetch_assoc(); $licznik++;?>

            <tr>
                <td><?php echo $licznik?></td>
                <td><?php echo $tablica_historia[data]?></td>

                <?php
                //sprawdzanie jaki user/admin znajduje się pod ID z 1.13
                $zapytanie_historia_admin = "SELECT * FROM hotline_users WHERE id_crm LIKE $tablica_historia[id_crm] ";
                $wynik_historia_admin = $db13->query($zapytanie_historia_admin);
                $tablica_historia_admin = $wynik_historia_admin->fetch_assoc();
                $nazwa = $tablica_historia_admin[imie]." ".$tablica_historia_admin[nazwisko];
                ;?>

                <td><?php echo $nazwa; ?></td>

                <?php
                //sprawdzanie jaki user się pod ID z 1.2
                $zapytanie_historia_user = "SELECT * FROM uzytkownicy_ewidencja WHERE id LIKE  $tablica_historia[id_user]";
                $wynik_historia_user = $db2->query($zapytanie_historia_user);
                $tablica_historia_user = $wynik_historia_user->fetch_assoc();
                $user = $tablica_historia_user[imie]." ".$tablica_historia_user[nazwisko];
                ?>

                <td><?php echo $user; ?></td>

                <?php
                if ($tablica_historia[id_oddzial]==0)
                {
                    echo "<td>n/d</td>";
                }
                else {
                    //sprawdzanie co znajduje sie pod id jednostki organizacyjnej z 1.13
                    $zapytanie_historia_oddzial = "SELECT * FROM jednostki_organizacyjne_ewidencja WHERE id LIKE  $tablica_historia[id_oddzial] ";
                    $wynik_historia_oddzial = $db13->query($zapytanie_historia_oddzial);
                    $tablica_historia_oddzial = $wynik_historia_oddzial->fetch_assoc();
                    $nazwa = $tablica_historia_oddzial[nazwa];

                    echo "<td>" . $nazwa . "</td>";
                }
                   ?>

                <td><?php echo $tablica_historia[id_akcja]?></td>

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