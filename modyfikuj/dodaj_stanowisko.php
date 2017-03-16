<?php
session_start();
//DODAWANIE STANOWISKA DODATKOWEGO - PLIK PHP
require_once ('../config/config.php');

$id = $_SESSION['pracownik'];
$oddzial = $_POST['oddzial'];
$stanowisko = $_POST['stanowisko'];
$one = 1;
$zero = 0;

//sprawdzenie czy zostało wybrane oddział oraz stanowisko
if (($oddzial == 0) || ($stanowisko == 0))
{
    $_SESSION['alert2'] = '<div class="alert alert-danger">Nie wybrano stanowiska lub oddziału docelowego</div>';
    header('location: ../modyfikuj.php?id='.$id);
    exit();
}

//sprawdzanie czy juz wczesniej istaniał wpis odnośnie przypinanego stanowiska
$zapytanie_powiazanie = "SELECT *  FROM pracownicy_stanowiska WHERE id_pracownika='$id' AND id_jednostki_organizacyjnej='$oddzial' AND id_stanowiska='$stanowisko'";
$wynik_powiazanie = $db2_hr->query($zapytanie_powiazanie);
$ilosc_powiazanie = $wynik_powiazanie->num_rows;

if ($ilosc_powiazanie>0)
{
    //jesli wpis wczesniej istniał, zmien tylko jego status=1 oraz czy_glowna=1
    $update_stanowiska_2 = "UPDATE pracownicy_stanowiska SET status=1 WHERE id_pracownika='$id' AND id_jednostki_organizacyjnej='$oddzial' AND id_stanowiska='$stanowisko'";
    $db2_hr->query($update_stanowiska_2);
}
else
{
    //jesli wspis wczesniej nie istaniał, stwórz powiązanie stanowiska
    $insert_powiazanie = "INSERT INTO `pracownicy_stanowiska` (`id`,`id_pracownika`,`id_jednostki_organizacyjnej`,`id_stanowiska`,`czy_glowne`,`status`) VALUES (NULL,'$id', '$oddzial','$stanowisko','$zero','$one')";
    $db2_hr->query($insert_powiazanie);
}



//historia dodawanie stanowisk
$id_admin = $_SESSION['id_pracownika'];
$akcja = 4;
$data = date("Y-m-d H:i:s");
$insert_historia = "INSERT INTO `hotline_historia` (`id`,`data`,`id_crm`,`id_user`,`id_oddzial`,`id_akcja`) VALUES (NULL,'$data','$id_admin','$id','$oddzial','$akcja')";
$db13->query($insert_historia);


//alert
$_SESSION['alert2'] = '<div class="alert alert-success">Stanowisko zostało pomyślnie dodane.</div>';
header('location: ../modyfikuj.php?id='.$id);

$db2->close();
$db13->close();
$db2_hr->close();
$db2_capital->close();
exit();