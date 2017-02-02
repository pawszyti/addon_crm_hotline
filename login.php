<?php
session_start();
if ($_POST['username']=="" || $_POST['password']=="")
{
    header('location: index.php');
    $_SESSION['error'] = '<div class="alert alert-danger">Pola nie mogą być puste</div>';
    exit();
}
require_once ('config/config.php');
$username = $_POST['username'];
$password = $_POST['password'];
$username = htmlentities($username, ENT_QUOTES, "UTF-8");//dodawanie encji
define('ZIARNO_MD5',md5('pswd55tughrtrtjgd%&$754'));
$pass = md5(ZIARNO_MD5.md5($password).ZIARNO_MD5);

if ($result = $db13->query(sprintf("SELECT * FROM hotline_users WHERE login='%s'",
    mysqli_real_escape_string($db13,$username))))
{
    $quantity = $result->num_rows;
    if($quantity>0)
    {
        $row =$result->fetch_assoc();
        $login = $row['login'];

        if($result2 = $db2->query("SELECT * FROM uzytkownicy_ewidencja WHERE login LIKE '$login' "))
        {
            $row2 =$result2->fetch_assoc();
            $pass_base = $row2['haslo'];

            if ($pass_base==$pass) {
                $_SESSION['username'] = $row['username'];
                $_SESSION['name'] = $row['name'];
                $_SESSION['surname'] = $row['surname'];
                $_SESSION['hotline'] = sha1(lock); //cookie logowania
                $_SESSION['ID_user'] = $row['ID_user'];
                setcookie("hotline", 'online', time() + 900); //czas życia cookie
                header('location: main.php');
            } else {
                $_SESSION['error'] = '<div class="alert alert-danger">Niepoprawne hasło</div>';
                header('location: index.php');
            }
        }
        else
        {
            $_SESSION['error'] = '<div class="alert alert-danger">Błąd weryfikacji hasła</div>';
            header('location: index.php');
        }

    }
    else
    {
        $_SESSION['error'] = '<div class="alert alert-danger">Niepoprawy login</div>';
        header('location: index.php');
    }
}
$db13->close();
$db2->close();

