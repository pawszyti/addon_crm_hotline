<?php
session_start();
require_once ('../config/config.php');

$id = $_SESSION['pracownik'];
$oddzial = $_POST['oddzial'];
$stanowisko = $_POST['stanowisko'];
$one = 1;
$zero = 0;

$zapytanie_powiazanie = "SELECT *  FROM pracownicy_stanowiska WHERE id_pracownika='$id' AND id_jednostki_organizacyjnej='$oddzial' AND id_stanowiska='$stanowisko'";
$wynik_powiazanie = $db2_hr->query($zapytanie_powiazanie);
$ilosc_powiazanie = $wynik_powiazanie->num_rows;

if ($ilosc_powiazanie>0)
{
    $update_stanowiska_2 = "UPDATE pracownicy_stanowiska SET status=1 WHERE id_pracownika='$id' AND id_jednostki_organizacyjnej='$oddzial' AND id_stanowiska='$stanowisko'";
    $db2_hr->query($update_stanowiska_2);
}
else
{
    $insert_powiazanie = "INSERT INTO `pracownicy_stanowiska` (`id`,`id_pracownika`,`id_jednostki_organizacyjnej`,`id_stanowiska`,`czy_glowne`,`status`) VALUES (NULL,'$id', '$oddzial','$stanowisko','$zero','$one')";
    $db2_hr->query($insert_powiazanie);
}

$_SESSION['alert2'] = '<div class="alert alert-success">Stanowisko zostało pomyślnie dodane.</div>';
header('location: ../modyfikuj.php?id='.$id);