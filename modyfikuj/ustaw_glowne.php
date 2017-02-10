<?php
session_start();
require_once ('../config/config.php');


$zapytanie_13 = "SELECT * FROM jednostki_organizacyjne_ewidencja WHERE id LIKE '$oddzial'";
$zapytanie_13_stanowisko = "SELECT * FROM stanowiska_ewidencja WHERE id LIKE '$stanowisko'";

$wynik_13 = $db13->query($zapytanie_13);
$tablica_13 = $wynik_13->fetch_assoc();
$oddzial_crm1 = $tablica_13['crm1_oddzialy'];
$dzial_crm1 = $tablica_13['crm1_dzialy'];
$wynik_13_stanowisko = $db13->query($zapytanie_13_stanowisko);
$tablica_13_stanowisko = $wynik_13_stanowisko->fetch_assoc();
$stanowisko_crm1 = $tablica_13_stanowisko['crm1_stanowisko'];


$id = $_GET['id'];
$id_pracownika = $_GET['id_pracownika'];
$update_stanowiska_1 = "UPDATE pracownicy_stanowiska SET czy_glowne=0 WHERE id_pracownika='$id_pracownika'";
$db2_hr->query($update_stanowiska_1);

$update_stanowiska_2 = "UPDATE pracownicy_stanowiska SET czy_glowne=1 WHERE id='$id'";
$db2_hr->query($update_stanowiska_2);

$update_uzytkownicy = "UPDATE uzytkownicy_ewidencja SET id_jednostki_organizacyjnej='$oddzial', id_stanowiska='$stanowisko', id_oddzialu='$oddzial_crm1' WHERE id_pracownika='$id_pracownika'";
$db2->query($update_uzytkownicy);

$update_capital ="UPDATE cash_users SET id_oddzialu='$oddzial_crm1', id_dzialu='$dzial_crm1', stanowisko='$stanowisko_crm1' WHERE user_id='$id_pracownika'";
$db2_capital->query($update_capital);


$_SESSION['alert2'] = '<div class="alert alert-success">Zmieniono pomyślnie stanowisko główne.</div>';
header('location: ../modyfikuj.php?id='.$id_pracownika);
$db2->close();
$db13->close();
$db2_hr->close();
$db2_capital->close();
exit();