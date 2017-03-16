<?php
session_start();
//USUNIĘCIE STANOWISKA - PLIK PHP
require_once ('../config/config.php');
$id = $_GET['id'];
$id_pracownika = $_GET['id_pracownika'];
$oddzial = $_GET['oddzial'];

//zmiana statusu powiązania stanowiska na 0
$update_stanowiska = "UPDATE pracownicy_stanowiska SET status=0, czy_glowne=0 WHERE id='$id'";
$db2_hr->query($update_stanowiska);




//historia usunięcia stanowiska
$id_admin = $_SESSION['id_pracownika'];
$akcja = 5;
$data = date("Y-m-d H:i:s");
$insert_historia = "INSERT INTO `hotline_historia` (`id`,`data`,`id_crm`,`id_user`,`id_oddzial`,`id_akcja`) VALUES (NULL,'$data','$id_admin','$id_pracownika','$oddzial','$akcja')";
$db13->query($insert_historia);

//alert
$_SESSION['alert2'] = '<div class="alert alert-success">Stanowisko zostało pomyślnie usunięte.</div>';
header('location: ../modyfikuj.php?id='.$id_pracownika);

$db2->close();
$db13->close();
$db2_hr->close();
$db2_capital->close();
exit();