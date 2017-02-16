<?php
session_start();
if(isset($_SESSION['hotline']) && $_SESSION['hotline'] == sha1(admin) && isset($_COOKIE['admin']))
{
require_once ('config/config.php');
$username = $_SESSION['username'];
$name = $_SESSION['name'];
$surname = $_SESSION['surname'];
setcookie("admin", 'online', time() + 1800); //czas życia cookie
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="author" content="Paweł Szymczyk" />
    <title>Panel Hotline</title>
    <link href="css/style.css" rel="stylesheet">
    <link href="css/bootstrap.css" rel="stylesheet">

    <script src="js/jquery-3.1.1.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/bootbox.min.js"></script> <!--plugin js do okien dialogowych (potwierdzenie) -->
</head>
<body>

<div class="navbar navbar-default btn-variants navbar-fixed" role="navigation">
    <div style="text-align: center" class="col-lg-8 col-lg-offset-2 col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3">
      <h2 class="font_logo ">ADMINISTRATOR HOTLINE</b></h2>
    </div>
  <div class="col-lg-1 col-lg-offset-1 col-md-2 col-md-offset-1 col-sm-2 col-sm-offset-1 col-sm-6 col-sm-offset-3 col-xs-4 col-xs-offset-4" ><a data-toggle="tooltip" data-placement="bottom" title="Zalogowany:<?php  echo "    ".$name." ".$surname; ?>" role="button" class="btn btn-default btn-sm " style="margin-top: 15px" href="logout.php">Wyloguj</a></div>
</div>
<?php
echo "<div>".$_SESSION['alert2']."</div>";
unset($_SESSION['alert2']);
?>
<div class="container">

    <a class="btn btn-success col-lg-2 col-lg-offset-3" disabled>Użytkownicy</a>
    <a href="admin/hotline_historia.php" class="btn btn-danger col-lg-2" style="margin-left: 6px;margin-right: 6px" >Historia akcji</a>
    <a href="admin/hotline_logowanie.php" class="btn btn-info col-lg-2" >Historia logowań</a>



    <a href="admin/hotline_users.php" class="btn btn-default col-lg-4 col-lg-offset-4" style="margin-top: 20px; margin-bottom: 20px" data-toggle="modal" data-target="#exampleModal">Dodaj użytkownika</a>

    <table class="table table-striped" cellspacing='0' style='text-align: center'>
<tr>
    <th style="text-align: center">lp.</th>
    <th style="text-align: center">crm - id</th>
    <th style="text-align: center">Imię</th>
    <th style="text-align: center">Nazwisko.</th>
    <th style="text-align: center">Login</th>
    <th style="text-align: center">Usuń</th>

</tr>
<?php
        $zapytanie_admins = "SELECT * FROM hotline_users";
        $wynik_admins = $db13->query($zapytanie_admins);
        $ilosc_admins = $wynik_admins->num_rows;
        $licznik=0;
        for ($i = 0; $i < $ilosc_admins; $i++)
        {
            ?>
            <?php
            $tablica_admins = $wynik_admins->fetch_assoc();
            $licznik++;
            ?>

            <tr>
            <td><?php echo $licznik; ?></td>
            <td><?php echo $tablica_admins[id_crm]; ?></td>
            <td><?php echo $tablica_admins[imie]; ?></td>
            <td><?php echo $tablica_admins[nazwisko]; ?></td>
            <td><?php echo $tablica_admins[login]; ?></td>
                <?php
                if ($tablica_admins[login]=='admin')
                {
                    echo "<td><a disabled class='btn btn-danger'>Usuń</a> </td>";
                }
            else
            {
                echo
                    "<td>
                     <a href='admin/usun.php?id=".$tablica_admins['id_crm']." class='btn btn-danger'>Usuń</a>
                     </td>";

            }
?>

                </tr>

                <?php
        }


// **** okno dialogowe - start ****
echo "<div class=\"modal fade\" id=\"exampleModal\" tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel'>
        <div class=\"modal-dialog\" role=\"document\">
            <div class=\"modal-content\">
                <div class=\"modal-header\">
                    <button type='button' class='close' data-dismiss='modal' aria-label=\"Close\"><span aria-hidden='true'>&times;</span></button>
                    <h4 id='exampleModalLabel'>Aby dodać, wpisz id użytkownia: <br /></h4><h4><b>".$tablica_hr[imie]." ".$tablica_hr[nazwisko]."</b></h4>
                </div>
                <div class='modal-body'>

                    <form action='admin/dodaj.php' method='post' name='dodaj'>

                       <input type='text' name='id_crm' maxlength=5 onkeyup='this.value=this.value.replace(/\D/g,'')'>
               </div>
               <div class='modal-footer'>
                    <button type='button' class=\"btn btn-default\" data-dismiss='modal'>Anuluj</button>
                    <button type='submi' class='btn btn-success'>Dodaj</button>
                    </form>
               </div>
         </div>
    </div>
</div>";

// **** okno dialogowe end ****


}
else
{
  header('location: logout.php');
  exit;
  //jesli pierwszy warunek nie został spełniony to prześlij to strony wylogowania
}
//LOGOWANIE - SPRAWDZENIE - STOP
    ?>


<?php
$db2->close();
$db13->close();
$db2_hr->close();
$db2_capital->close();
?>

</body>
</html>