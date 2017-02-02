<?php
session_start();
if(isset($_SESSION['hotline']) && $_SESSION['hotline'] == sha1(lock) && isset($_COOKIE['hotline']))
{
setcookie("hotline",'online', time()+900);
require_once ('config/config.php');
$username = $_SESSION['username'];
$name = $_SESSION['name'];
$surname = $_SESSION['surname'];
$limit = 0;
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="author" content="Paweł Szymczyk" />
    <title>Hotline</title>
    <link href="css/style.css" rel="stylesheet">
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-theme.css" rel="stylesheet">
    <script src="js/jquery-3.1.1.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/bootbox.min.js"></script>


</head>
<body>


<nav class="navbar navbar-default btn-variants navbar-fixed" role="navigation">
<div style="text-align: center">
<h2>Panel Hotline</h2>
</div>


</nav>


<div class="container">
    <?php
    echo $_SESSION['alert2'];
    unset($_SESSION['alert2']);
    ?>
    <form action='' method="post">
<table class="tabela3">
    <tr>
        <th style="text-align: center">Imię</th>
        <th style="text-align: center">Nazwisko</th>
    </tr>
    <tr>
        <th><input type="text"  name="imie" size="40px"></th>
        <th><input type="text"  name="nazwisko" size="40px"></th>
    </tr>
    <tr>
        <th colspan="2"><button type="submit" class="btn btn-primary form-control">Wyszukaj</button> </th>
    </tr>
</table>
    </form>




    <?php
    if(isset($_POST['imie'])||($_POST['nazwisko'])){

        if(($_POST['imie']=="") && ($_POST['nazwisko']=="")){
        $_SESSION['alert'] = '<div class="alert alert-danger">Nic nie wpisano.</div>';}
        else{




        if(($_POST['imie']==''))
        $lp = 0;
        $imie = $_POST['imie'];
        $nazwisko = $_POST['nazwisko'];
        $zapytanie = "SELECT *  FROM jednostki_organizacyjne_ewidencja, uzytkownicy_ewidencja 
                      WHERE jednostki_organizacyjne_ewidencja.id = uzytkownicy_ewidencja.id_jednostki_organizacyjnej AND nazwisko LIKE '%$nazwisko%' AND imie LIKE '%$imie%'" ;
        if ($wynik = $db2->query($zapytanie)) {
            $ilosc = $wynik->num_rows;
            $licznik = $ilosc;

            if ($ilosc<1){
                $_SESSION['alert'] = '<div class="alert alert-danger">Nie znaleziono pasującego użytkownika w systemie.</div>';

            }
            else
                {

                    echo" <table class=\"tabela2\" cellspacing='0'>
            <tr>
            <th style=\"text-align: center\">lp.</th>
            <th style=\"text-align: center\">Imię</th>
            <th style=\"text-align: center\">Nazwisko</th>
            <th style=\"text-align: center\">Jednostka Org.</th>
            <th style=\"text-align: center\">Czy aktywny</th>
            <th style=\"text-align: center\">Odblokuj</th>
            <th style=\"text-align: center\">Przepnij</th>
              </tr>";


            for ($i = 0; $i < $ilosc; $i++) {
                $tablica = $wynik->fetch_assoc();
                $pesel = $tablica['pesel'];

                $zapytanie_hr = "SELECT * FROM pracownicy_ewidencja WHERE pesel LIKE '$pesel'";
                $wynik_hr = $db2_hr->query($zapytanie_hr);
                $tablica_hr = $wynik_hr->fetch_assoc();
                $pracuje = $tablica_hr['czy_pracuje'];
                if ($pracuje == 0) {
                    continue;
                }
                $limit = $limit+1;
                if($limit == 26)
                {
                    $_SESSION['alert'] = '<div class="alert alert-danger">Znaleziono więcej niż 25 wyników, zawęź kryteria wyszukiwania.</div>';
                    break;

                }
                $lp = $lp + 1;
                echo "
            <tr>
        <td>" . $lp . "</td>
        <td>" . $tablica['imie'] . "</td>
        <td>" . $tablica['nazwisko'] . "</td>
        <td>" . $tablica['nazwa'] . "</td>
        <td>";

                if ($tablica['status'] == 1) {
                    echo "<img src=\"img/ok.png\" width='25px'>";
                } else {
                    echo "<img src=\"img/no.png\" width='25px'>";
                }


                echo "</td>";


                echo "<td><input type=submit class=\"btn btn-danger\" value='Odblokuj' onclick=\"bootbox.confirm('Czy chcesz odblokować użytkownika<b> ".$tablica['imie']." ".$tablica['nazwisko']."</b>  zmienić jego hasło na PESEL?', function(result){ if (result==true) {window.location.href='odblokuj.php?id=".$tablica['pesel']."'}; });\" class=\"myButton2\"></td>";

                echo "<td><input type=submit class=\"btn btn-warning\" value='Przepnij' onclick=\"bootbox.confirm('Czy chcesz z listy usunąć użytkownika<b> ".$tablica['imie']." ".$tablica['nazwisko']."</b> i zmienić jego hasło na PESEL ?', function(result){ if (result==true) {window.location.href='del.php?id=".$tablica['pesel']."'}; });\" class=\"myButton2\"></td>";



                echo "</tr>";
            }}
        } else
            echo "error";
    }}


    ?>


</table>
    <?php
    echo $_SESSION['alert'];
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
</div>
</body>
</html>