<?php
session_start();
$login = $_GET['login'];

$script="/usr/local/bin/hotline/samba_pass.sh $login";
shell_exec($script); //uruchamia skrypt na 1.13

$_SESSION['alert2'] = '<div class="alert alert-success">Zmieniono hasło domenowe dla użytkownika '.$login.' na <b>Capital1</b></div>';
header('location: main.php');