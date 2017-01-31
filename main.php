<?php
session_start();
require_once ('config/config.php');

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="author" content="Paweł Szymczyk" />
    <title>Hotline</title>
    <link href="css/style.css" rel="stylesheet">
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-theme.css" rel="stylesheet">
    <script src="js/jquery-3.1.1.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/bootbox.min.js"></script>

</head>
<body>


<nav class="navbar navbar-default btn-variants navbar-fixed" role="navigation">
<div style="text-align: center">
<h2>Panel Hotline</h2>
</div>


</nav>

<?php
echo $_SESSION['alert'];
session_unset($_SESSION['alert']);


?>
<div class="container">
    <form action='' method="post">
<table class="tabela3">
    <tr>
        <th style="text-align: center">Imię</th>
        <th style="text-align: center">Nazwisko</th>
    </tr>
    <tr>
        <th><input type="text"  name="imie" size="40px"></th>
        <th><input type="text"  name="nazwisko" size="40px"></th>
    </tr>
    <tr>
        <th colspan="2"><button type="submit" class="btn btn-primary form-control">Wyszukaj</button> </th>
    </tr>
</table>
    </form>


<table class="tabela2" cellspacing='0'>
    <tr>
        <th style="text-align: center">lp.</th>
        <th style="text-align: center">Imię</th>
        <th style="text-align: center">Nazwisko</th>
        <th style="text-align: center">Jednostka Org.</th>
        <th style="text-align: center">Czy aktywny</th>
        <th style="text-align: center">Odblokuj</th>
        <th style="text-align: center">Przepnij</th>
    </tr>

    <?php
    $imie = $_POST['imie'];
    $nazwisko = $_POST['nazwisko'];
    if($zapytanie = "SELECT *  FROM jednostki_organizacyjne_ewidencja, uzytkownicy_ewidencja 
                      WHERE jednostki_organizacyjne_ewidencja.id = uzytkownicy_ewidencja.id_jednostki_organizacyjnej AND nazwisko LIKE '%$nazwisko%' AND imie LIKE '%$imie%'") {
        $wynik = $db2->query($zapytanie);
        $ilosc = $wynik->num_rows;
        $licznik = $ilosc;
        for ($i = 0; $i < $ilosc; $i++) {
            $lp = $i+1;
            $tablica = $wynik->fetch_assoc();
            echo "
            <tr>
        <td>" . $lp . "</td>
        <td>" . $tablica['imie'] . "</td>
        <td>" . $tablica['nazwisko'] . "</td>
        <td>" . $tablica['nazwa'] . "</td>
        <td>";

        if ($tablica['status']==1){
            echo"<img src=\"img/ok.png\" width='25px'>";
        }
        else
        {
            echo"<img src=\"img/no.png\" width='25px'>";
        }




        echo"</td>
        
        
        
        <td><button class='btn btn-danger'>Odblokuj</button></td>
        <td><button class='btn btn-warning'>Przepnij</button></td>";

            echo "</tr>";
        }
    }
    else
        echo "error";
    ?>




</table>
</div>
</body>
</html>