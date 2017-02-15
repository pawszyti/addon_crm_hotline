<?php
session_start();
require('config/config.php');
$pesel = $_GET['pesel'];
$idi = $_GET['id'];
$pass = md5(ZIARNO_MD5.md5($pesel).ZIARNO_MD5); //haszowanie hasła do crm

$update = "UPDATE uzytkownicy_ewidencja SET status=1, haslo='$pass' WHERE id LIKE '$idi'";
$db2->query($update);




$zapytanie_pracwnik = "SELECT * FROM uzytkownicy_ewidencja WHERE id LIKE  $idi";
$wynik_pracownik = $db2->query($zapytanie_pracwnik);
$tablica_pracownik = $wynik_pracownik->fetch_assoc();
$id_pracownik = $tablica_pracownik['id'];

$imie = $tablica_pracownik['imie'];
$nazwisko = $tablica_pracownik['nazwisko'];


//historia
$id_admin = $_SESSION['id_pracownika'];
$akcja = 1;
$data = date("Y-m-d H:i:s");
$oddzial = 0;
$insert_historia = "INSERT INTO `hotline_historia` (`id`,`data`,`id_crm`,`id_user`,`id_oddzial`,`id_akcja`) VALUES (NULL,'$data','$id_admin','$id_pracownik','$oddzial','$akcja')";
$db13->query($insert_historia);


$_SESSION['alert2'] = '<div class="alert alert-success">Konto użytkownika '.$imie.' '.$nazwisko.' zostało odblokowane. <br />Należy zamknąć wszystkie przeglądarki i jako hasło wpisać numer PESEL.</div>';
header('location: main.php');

$db2->close();
$db13->close();
$db2_hr->close();
$db2_capital->close();
exit();

