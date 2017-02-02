<?php
session_start();
if (isset($_SESSION['hotline']) && $_SESSION['hotline'] == sha1(lock) && isset($_COOKIE['hotline']))
{
    header('location: main.php');
    exit();
}
require_once ('config/config.php');
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="author" content="Paweł Szymczyk" />
    <title>Baza Wiedzy</title>

    <link href="css/style.css" rel="stylesheet">
    <!--Bootstrap CSS-->
    <link href="css/bootstrap.css" rel="stylesheet">


</head>
<body>
<div class="container main" style="margin-top: 60px">
    <div class="row login">
        <div class="col-lg-4 col-md-4 col-sm-6 col-lg-offset-4 col-md-offset-4 col-sm-offset-3">

            <h1><span class="text-center"> Panel Hotline </h1></span>
            <div class="alert alert-warning">Wymagane jest zalogowanie</div>

            <form action="login.php" method="post">
                <div class="form-group">
                    Login: <input type="text" name="username" class="form-control" placeholder="Login" autocomplete="off">
                </div>
                <div class="form-group">
                    Hasło: <input type="password" name="password" class="form-control" placeholder="Hasło" autocomplete="off">
                </div>
                <button type="submit" class="btn btn-primary">Zaloguj</button>
        </div>
        </form>


    </div>
    <br />

    <?php
    if(isset($_SESSION['error'])) {
        $error = $_SESSION['error'];
        unset($_SESSION['error']);
        echo "<br />" . $error;
    }




    ?>
</div> <!-- /container -->
</body>
</html>




