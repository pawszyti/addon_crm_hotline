<?php
session_start();
//LOGOWANIE UŻYTKOWNIKA - PLIK PHP
if ($_POST['username']=="" || $_POST['password']=="")
{
    //alert jedno z pól jest puste
    header('location: index.php');
    $_SESSION['error'] = '<div class="alert alert-danger">Pola nie mogą być puste</div>';
    exit();
}
require_once ('config/config.php');
$username = $_POST['username'];
$password = $_POST['password'];
$username = htmlentities($username, ENT_QUOTES, "UTF-8");//dodawanie encji
$pass = md5(ZIARNO_MD5.md5($password).ZIARNO_MD5); //haszowanie hasła, tak jak w CRM
$ip = $_SESSION[ip];

//sprawdzanie czy użytkownik istnieje w bazie 1.13
if ($result = $db13->query(sprintf("SELECT * FROM hotline_users WHERE login='%s'",
    mysqli_real_escape_string($db13,$username))))
{
    $quantity = $result->num_rows;
    if($quantity>0) {
        $row = $result->fetch_assoc();
        $login = $row['login'];

        //jesli użytkownik to admin
        if ($username == 'admin') {
            //pobierz z bazy dane użytkownika o podanym loginie
            if ($result2 = $db13->query("SELECT * FROM hotline_users WHERE login LIKE '$username' ")) {
                $row2 = $result2->fetch_assoc();
                $pass_base = $row2['pass'];
                //sprawdzanie czy hasło się zgadza z tym który zostało zapisane w 1.13 (tylko admin)
                if ($pass_base == $pass) {
                    //podstawianie parametrów do zmiennych sesyjnych, login, imie, nazwisko, id
                    $_SESSION['username'] = $row['login'];
                    $_SESSION['name'] = $row['imie'];
                    $_SESSION['id_pracownika'] = $row['id_crm'];
                    $_SESSION['surname'] = '';
                    $_SESSION['hotline'] = sha1(admin);
                    //tworzenie dodatkowego ciasteczka który ma czas życia 15 minut
                    setcookie("admin", 'online', time() + 1800);

                    //historia
                    $id_admin = $_SESSION['id_pracownika'];
                    $data = date("Y-m-d H:i:s");
                    $insert_historia = "INSERT INTO `hotline_logowania` (`id_logowanie`,`data_logowanie`,`user_logowanie`,`ip_logowanie`) VALUES (NULL,'$data','$id_admin','$ip')";
                    //zapisuje to bazy 1.13 informacje, że dany użytkownik się zalogował
                    $db13->query($insert_historia);

                    //przekirowanie zalogowane uzytkownika do strony admin.php
                    header('location: admin.php');
                } else {
                    //alert niepoprawne hasło
                    $_SESSION['error'] = '<div class="alert alert-danger">Niepoprawne hasło</div>';
                    header('location: index.php');
                }
            } else {
                //alert błąd weryfikacji, spowodowany problemem z zapytaniem lub bazą danych
                $_SESSION['error'] = '<div class="alert alert-danger">Błąd weryfikacji hasła</div>';
                header('location: index.php');
            }
        }
        else
        {
        //pobieranie danych dotyczących wybranego użytkownika z bazy 1.2
        if ($result2 = $db2->query("SELECT * FROM uzytkownicy_ewidencja WHERE login LIKE '$login' ")) {
            $row2 = $result2->fetch_assoc();
            $pass_base = $row2['haslo'];

            //sprawdzanie poprawności hasła z tym zapisanym w CRM
            if ($pass_base == $pass) {
                $_SESSION['username'] = $row['login'];
                $_SESSION['name'] = $row['imie'];
                $_SESSION['id_pracownika'] = $row['id_crm'];
                $_SESSION['surname'] = $row['nazwisko'];
                $_SESSION['hotline'] = sha1(lock); //cookie logowania
                //użytkownika dla których czas sesji został wydłużony
                if (($username == 'k.szpond') || ($username == 'p.szymczyk') || ($username == 'm.pianka') || ($username == 'p.jakacki')) {
                    setcookie("hotline", 'online', time() + 99900); //czas życia cookie
                } else {
                    setcookie("hotline", 'online', time() + 1800); //czas życia cookie
                }
                //historia
                $id_admin = $_SESSION['id_pracownika'];
                $data = date("Y-m-d H:i:s");
                $insert_historia = "INSERT INTO `hotline_logowania` (`id_logowanie`,`data_logowanie`,`user_logowanie`,`ip_logowanie`) VALUES (NULL,'$data','$id_admin','$ip')";
                //zapisuje to bazy 1.13 informacje, że dany użytkownik się zalogował
                $db13->query($insert_historia);

                //przekieruj do main.php
                header('location: main.php');
            } else {
                //alert niepoprawne hasło
                $_SESSION['error'] = '<div class="alert alert-danger">Niepoprawne hasło</div>';
                header('location: index.php');
            }
        } else {
            //alert błąd weryfikacji, spowodowany problemem z zapytaniem lub bazą danych
            $_SESSION['error'] = '<div class="alert alert-danger">Błąd weryfikacji hasła</div>';
            header('location: index.php');
        }
    }

    }
    else
    {
        //alert niepoprawny login
        $_SESSION['error'] = '<div class="alert alert-danger">Niepoprawy login</div>';
        header('location: index.php');
    }
}
$db2->close();
$db13->close();
$db2_hr->close();
$db2_capital->close();
exit();

