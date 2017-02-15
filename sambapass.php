<?php
session_start();
require('config/config.php');

$login = $_GET['login'];
$id_pracownika = $_GET['id'];

$script="/usr/local/bin/hotline/samba_pass.sh $login";
shell_exec($script); //uruchamia skrypt na 1.13

//historia
$oddzial = 0;
$id_admin = $_SESSION['id_pracownika'];
$akcja = 6;
$data = date("Y-m-d H:i:s");
$insert_historia = "INSERT INTO `hotline_historia` (`id`,`data`,`id_crm`,`id_user`,`id_oddzial`,`id_akcja`) VALUES (NULL,'$data','$id_admin','$id_pracownika','$oddzial','$akcja')";
$db13->query($insert_historia);


$_SESSION['alert2'] = '<div class="alert alert-success">Zmieniono hasło domenowe dla użytkownika '.$login.' na <b>Capital1</b></div>';
header('location: main.php');

$db2->close();
$db13->close();
$db2_hr->close();
$db2_capital->close();
exit();