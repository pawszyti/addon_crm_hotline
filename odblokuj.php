<?php
session_start();
require('config/config.php');
$pesel = $_GET['id'];
define('ZIARNO_MD5',md5('pswd55tughrtrtjgd%&$754'));

$pass = md5(ZIARNO_MD5.md5($pesel).ZIARNO_MD5); //haszowanie hasła do crm

$update = "UPDATE uzytkownicy_ewidencja SET status=1, haslo='$pass' WHERE pesel LIKE '$pesel'";
$db2->query($update);

$_SESSION['alert2'] = '<div class="alert alert-success">Konto użytkownika zostało odblokowane <br />Należy zamknąć wszystkie przeglądarki i jako hasło wpisać numer PESEL</div>';
header('location: main.php');

$db2->close();
$db13->close();
$db2_hr->close();
$db2_capital->close();
exit();

