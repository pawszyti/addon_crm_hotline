<?php
session_start();
//USUNIĘCIE DOSTĘPU DO HOTLINE
require_once ('../config/config.php');
$id = $_GET['id'];

//usun usera/admina z bazy 1.13
$delete = "DELETE FROM hotline_users WHERE id_crm LIKE $id";

$db13->query($delete);

$_SESSION['alert2'] = '<div class="alert alert-success">Pomyslnie usunięto użytkownika.</div>';
header('location: ../admin.php');

$db2->close();
$db13->close();
$db2_hr->close();
$db2_capital->close();
exit();