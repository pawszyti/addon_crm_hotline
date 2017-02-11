<?php
session_start();
$login = $_GET['login'];
$script="/usr/local/bin/hotline/sambapass.sh $login";
//$message = shell_exec($script);
$_SESSION['alert2'] = '<div class="alert alert-success">Zmieniono hasło domenowe dla użytkownika '.$login.' na <b>Capital1</b></div>';
header('location: main.php');