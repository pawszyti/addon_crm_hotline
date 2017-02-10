<?php
session_start();
require_once ('../config/config.php');
$id = $_GET['id'];
$id_pracownika = $_GET['id_pracownika'];
$update_stanowiska_1 = "UPDATE pracownicy_stanowiska SET czy_glowne=0 WHERE id_pracownika='$id_pracownika'";
$db2_hr->query($update_stanowiska_1);
$update_stanowiska_2 = "UPDATE pracownicy_stanowiska SET czy_glowne=1 WHERE id='$id'";
$db2_hr->query($update_stanowiska_2);
$_SESSION['alert2'] = '<div class="alert alert-success">Zmieniono pomyślnie stanowisko główne.</div>';
header('location: ../modyfikuj.php?id='.$id_pracownika);