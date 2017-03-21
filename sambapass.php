<?php
session_start();
//ZMIANA HASŁA SAMBA - PLIK PHP
require('config/config.php');

$login = $_GET['login'];
$id_pracownika = $_GET['id'];

//uruchom skrypt z 1.13 z ścieżki poniżej
$script="/usr/local/bin/hotline/samba_pass.sh $login";
shell_exec($script); //uruchamia skrypt na 1.13

//historia resetu haseł SAMBA
$oddzial = 0;
$id_admin = $_SESSION['id_pracownika'];
$akcja = 6;
$data = date("Y-m-d H:i:s");
$insert_historia = "INSERT INTO `hotline_historia` (`id`,`data`,`id_crm`,`id_user`,`id_oddzial`,`id_akcja`) VALUES (NULL,'$data','$id_admin','$id_pracownika','$oddzial','$akcja')";
$db13->query($insert_historia);

//alert
$_SESSION['alert2'] = '<div class="alert alert-success">Zmieniono hasło domenowe dla użytkownika '.$login.' na <b>Capital1</b></div>';


//jesli istnieje zmienna imie OR naziwsko
if(isset($_SESSION['name'])||($_SESSION['surname']))
{
    //jesli zmienna imie AND naziwsko jest pusta
    if(($_SESSION['name']=="") && ($_SESSION['surname']=="")) {
        header('location: main.php');

    }
    echo
        "
        <div style='display: none'>
        <form action='main.php' method='post' name='". $tablica[id] ."'>
            <input type='hidden' value='".$_SESSION['name']."' name='imie'>
            <input type='hidden' value='".$_SESSION['surname']."' name='nazwisko'>
            <button type='submit' class='btn btn-primary btn-sm' id='formButton'>Powrót</button>
            </form>
        <script language=\"javascript\">
        document.getElementById(\"formButton\").click();
    </script>
</div>        
        "

    ;




}
else {
    header('location: main.php');

}
$db2->close();
$db13->close();
$db2_hr->close();
$db2_capital->close();
exit();