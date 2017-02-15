<?php
session_start();
require('../config/config.php');

$id_crm = $_POST['id_crm'];


$zapytanie_dodaj = "SELECT * FROM uzytkownicy_ewidencja WHERE id LIKE '$id_crm'";
$wynik_dodaj = $db2->query($zapytanie_dodaj);
$tablica_dodaj = $wynik_dodaj->fetch_assoc();
$ilosc_dodaj = $wynik_dodaj->num_rows;

if ($ilosc_dodaj==1){

    $zapytanie_dodaj2 = "SELECT * FROM hotline_users WHERE id_crm LIKE '$id_crm'";
    $wynik_dodaj2 = $db13->query($zapytanie_dodaj2);
    $tablica_dodaj2 = $wynik_dodaj2->fetch_assoc();
    $ilosc_dodaj2 = $wynik_dodaj2->num_rows;

    if ($ilosc_dodaj2==0)
    {

        $imie = $tablica_dodaj['imie'];
        $nazwisko = $tablica_dodaj['nazwisko'];
        $login = $tablica_dodaj['login'];
        $pass = 0;


        $insert_dodaj = "INSERT INTO `hotline_users`(`id`,`id_crm`,`imie`,`nazwisko`,`login`,`pass`) VALUES (NULL,'$id_crm','$imie','$nazwisko','$login','$pass')";
        if($db13->query($insert_dodaj))
        {
        $_SESSION['alert2'] = '<div class="alert alert-success">Dodano użytkownika</div>';
        header('location: ../admin.php');
        }
        else
        {
        $_SESSION['alert2'] = '<div class="alert alert-danger">Error base INSERT</div>';
        header('location: ../admin.php');
        }
    }
    else
    {
        $_SESSION['alert2'] = '<div class="alert alert-danger">Taki użytkownik już istnieje na liście</div>';
        header('location: ../admin.php');

    }




}
else
{
    $_SESSION['alert2'] = '<div class="alert alert-danger">Nie istnieje użytkownik o tak ID</div>';
    header('location: ../admin.php');


}

$db2->close();
$db13->close();
$db2_hr->close();
$db2_capital->close();