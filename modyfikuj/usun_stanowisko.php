<?php
session_start();
require_once ('../config/config.php');
$id = $_GET['id'];
$id_pracownika = $_GET['id_pracownika'];

$update_stanowiska = "UPDATE pracownicy_stanowiska SET status=0, czy_glowne=0 WHERE id='$id'";
$db2_hr->query($update_stanowiska);

$_SESSION['alert2'] = '<div class="alert alert-success">Stanowisko zostało pomyślnie usunięte.</div>';
header('location: ../modyfikuj.php?id='.$id_pracownika);

$db2->close();
$db13->close();
$db2_hr->close();
$db2_capital->close();
exit();