<?php
session_start();
require_once ('../config/config.php');

$id = $_GET['id'];
$id_pracownika = $_GET['id_pracownika'];


$zapytanie_stanowisko = "SELECT * FROM pracownicy_stanowiska WHERE id LIKE '$id'";
$wynik_stanowisko = $db2_hr->query($zapytanie_stanowisko);
$tablica_stanowisko = $wynik_stanowisko->fetch_assoc();
//pobieranie z tabeli pracownicy_stanowiska - id jednostki oraz id stanowiska w celu odpytania 1.13

$oddzial = $tablica_stanowisko['id_jednostki_organizacyjnej'];
$stanowisko = $tablica_stanowisko['id_stanowiska'];

$zapytanie_13 = "SELECT * FROM jednostki_organizacyjne_ewidencja WHERE id LIKE '$oddzial'";
$zapytanie_13_stanowisko = "SELECT * FROM stanowiska_ewidencja WHERE id LIKE '$stanowisko'";

$wynik_13 = $db13->query($zapytanie_13);
$tablica_13 = $wynik_13->fetch_assoc();
$oddzial_crm1 = $tablica_13['crm1_oddzialy'];
$dzial_crm1 = $tablica_13['crm1_dzialy'];
$wynik_13_stanowisko = $db13->query($zapytanie_13_stanowisko);
$tablica_13_stanowisko = $wynik_13_stanowisko->fetch_assoc();
$stanowisko_crm1 = $tablica_13_stanowisko['crm1_stanowisko'];
// pobieranie z bazy 1.13, powiązań stanowisk/oddziałów pomniędzy CRM1 a CRM2. Na podstawie wcześniej przesłanych id z input/select

$update_stanowiska_1 = "UPDATE pracownicy_stanowiska SET czy_glowne=0 WHERE id_pracownika='$id_pracownika'";
$db2_hr->query($update_stanowiska_1); //obecne stanowisko zmień na niekatywne "status=0"

$update_stanowiska_2 = "UPDATE pracownicy_stanowiska SET czy_glowne=1 WHERE id='$id'";
$db2_hr->query($update_stanowiska_2); //zmien wwybrane powiazanie stanowiska po id na status=1

$update_uzytkownicy = "UPDATE uzytkownicy_ewidencja SET id_jednostki_organizacyjnej='$oddzial', id_stanowiska='$stanowisko', id_oddzialu='$oddzial_crm1' WHERE id_pracownika='$id_pracownika'";
$db2->query($update_uzytkownicy);
//zmiana danych w uzytkownicy ewidencja - centrum

$update_capital ="UPDATE cash_users SET id_oddzialu='$oddzial_crm1', id_dzialu='$dzial_crm1', stanowisko='$stanowisko_crm1' WHERE user_id='$id_pracownika'";
$db2_capital->query($update_capital);
//zmiana w crm1


//historia
$id_admin = $_SESSION['id_pracownika'];
$akcja = 3;
$data = date("Y-m-d H:i:s");
$insert_historia = "INSERT INTO `hotline_historia` (`id`,`data`,`id_user_admin`,`id_user`,`id_oddzial`,`id_akcja`) VALUES (NULL,'$data','$id_admin','$id_pracownika','$oddzial','$akcja')";
$db13->query($insert_historia);



$_SESSION['alert2'] = '<div class="alert alert-success">Zmieniono pomyślnie stanowisko główne.</div>';
header('location: ../modyfikuj.php?id='.$id_pracownika);



$db2->close();
$db13->close();
$db2_hr->close();
$db2_capital->close();
exit;