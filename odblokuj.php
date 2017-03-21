<?php
session_start();
//ODBLOKOWANIE UŻYTKOWNIKA W CRM - PLIK PHP
require('config/config.php');
//pobieranie z tablicy GET
$pesel = $_GET['pesel'];
$idi = $_GET['id'];
$pass = md5(ZIARNO_MD5.md5($pesel).ZIARNO_MD5); //haszowanie hasła do CRM

//edycja danych w bazie CENTRUM - czy aktywny = 1 oraz zmiana hasła na pesel
$update = "UPDATE uzytkownicy_ewidencja SET status=1, haslo='$pass' WHERE id LIKE '$idi'";
$db2->query($update);

//podbranie danych pracownika w celu wyświetlania jego danych w alercie
$zapytanie_pracwnik = "SELECT * FROM uzytkownicy_ewidencja WHERE id LIKE  $idi";
$wynik_pracownik = $db2->query($zapytanie_pracwnik);
$tablica_pracownik = $wynik_pracownik->fetch_assoc();
$id_pracownik = $tablica_pracownik['id'];

$imie = $tablica_pracownik['imie'];
$nazwisko = $tablica_pracownik['nazwisko'];


//historia odblokowań
$id_admin = $_SESSION['id_pracownika'];
$akcja = 1;
$data = date("Y-m-d H:i:s");
$oddzial = 0;
$insert_historia = "INSERT INTO `hotline_historia` (`id`,`data`,`id_crm`,`id_user`,`id_oddzial`,`id_akcja`) VALUES (NULL,'$data','$id_admin','$id_pracownik','$oddzial','$akcja')";
$db13->query($insert_historia);

//dodanie alertu
$_SESSION['alert2'] = '<div class="alert alert-success">Konto użytkownika '.$imie.' '.$nazwisko.' zostało odblokowane. <br />Należy zamknąć wszystkie przeglądarki i jako hasło wpisać numer PESEL.</div>';




//jesli istnieje zmienna imie OR naziwsko
if(isset($_SESSION['name_back'])||($_SESSION['surname_back']))
{
    //jesli zmienna imie AND naziwsko jest pusta
    if(($_SESSION['name_back']=="") && ($_SESSION['surname_back']=="")) {
        header('location: main.php');

    }
    echo
        "
        <div style='display: none'>
        <form action='main.php' method='post' name='". $tablica[id] ."'>
            <input type='hidden' value='".$_SESSION['name_back']."' name='imie'>
            <input type='hidden' value='".$_SESSION['surname_back']."' name='nazwisko'>
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

