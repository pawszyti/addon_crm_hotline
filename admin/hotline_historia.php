<?php
session_start();
if(isset($_SESSION['hotline']) && $_SESSION['hotline'] == sha1(lock) && isset($_COOKIE['hotline']))
{
require_once ('../config/config.php');
$username = $_SESSION['username'];
$name = $_SESSION['name'];
$surname = $_SESSION['surname'];
$limit = 0;
if (($username=='k.szpond')||($username=='p.szymczyk'))
{
    setcookie("hotline", 'online', time() + 9900); //czas życia cookie
}
else
{
    setcookie("hotline", 'online', time() + 900); //czas życia cookie
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="author" content="Paweł Szymczyk" />
    <title>Panel Hotline</title>
    <link href="../css/style.css" rel="stylesheet">
    <link href="../css/bootstrap.css" rel="stylesheet">
    <link href="../css/set1.css" rel="stylesheet"> <!--plugin to bajeranckich input -->

    <script src="../js/jquery-3.1.1.js"></script>
    <script src="../js/bootstrap.js"></script>
    <script src="../js/bootbox.min.js"></script> <!--plugin js do okien dialogowych (potwierdzenie) -->
</head>
<body>

<div class="navbar navbar-default btn-variants navbar-fixed" role="navigation">
    <div style="text-align: center" class="col-lg-8 col-lg-offset-2 col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3">
        <h2 class="font_logo ">ADMINISTRATOR HOTLINE</b></h2>
    </div>
    <div class="col-lg-1 col-lg-offset-1 col-md-2 col-md-offset-1 col-sm-2 col-sm-offset-1 col-sm-6 col-sm-offset-3 col-xs-4 col-xs-offset-4" ><a data-toggle="tooltip" data-placement="bottom" title="Zalogowany:<?php  echo "    ".$name." ".$surname; ?>" role="button" class="btn btn-default btn-sm " style="margin-top: 15px" href="logout.php">Wyloguj</a></div>
</div>

<div class="container">

    <a href="../admin.php a" class="btn btn-success col-lg-2 col-lg-offset-3">Użytkownicy</a>
    <a class="btn btn-danger col-lg-2" style="margin-left: 6px;margin-right: 6px" disabled >Historia akcji</a>
    <a href="admin/hotline_users.php" class="btn btn-info col-lg-2" >Historia logowań</a>



    <a href="admin/hotline_users.php" class="btn btn-default col-lg-4 col-lg-offset-4" style="margin-top: 20px; margin-bottom: 20px" >Dodaj użytkownika</a>

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
        $zapytanie_historia = "SELECT * FROM hotline_historia, hotline_akcja, hotline_users WHERE hotline_historia.id_akcja = hotline_akcja.id AND hotline_historia.id_user_admin = hotline_users.id_crm";
        $wynik_historia = $db13->query($zapytanie_historia);
        $ilosc_historia = $wynik_historia->num_rows;
        $licznik=0;
        for ($i = 0; $i < $ilosc_historia; $i++){?>
            <?php $tablica_historia = $wynik_historia->fetch_assoc(); $licznik++;?>

            <tr>
                <td><?php echo $licznik?></td>
                <td><?php echo $tablica_historia[data]?></td>
                <td><?php echo $tablica_historia[id_user_admin]?></td>
                <td><?php echo $tablica_historia[id_user]?></td>
                <td><?php echo $tablica_historia[id_oddzial]?></td>
                <td><?php echo $tablica_historia[id_akcja]?></td>

            </tr>









            <?php
        }
        echo "<div>".$_SESSION['alert']."</div>";
        unset($_SESSION['alert']);
        }
        else
        {
            header('location: logout.php');
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