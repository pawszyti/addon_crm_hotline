<?php
session_start();
if(isset($_SESSION['hotline']) && $_SESSION['hotline'] == sha1(lock) && isset($_COOKIE['hotline']))
{
require_once ('config/config.php');
$username = $_SESSION['username'];
$name = $_SESSION['name'];
$surname = $_SESSION['surname'];
$limit = 0;
    if (($username=='k.szpond')||($username=='p.szymczyk'))
        {
            setcookie("hotline", 'online', time() + 9900); //czas życia cookie
        }
    else
        {
            setcookie("hotline", 'online', time() + 900); //czas życia cookie
        }
$id = $_GET[id];
$zapytanie = "SELECT *  FROM uzytkownicy_ewidencja WHERE id='$id'" ;
if ($wynik = $db2->query($zapytanie)) {
$ilosc = $wynik->num_rows;
$tablica = $wynik->fetch_assoc();
$licznik = 1;
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
    <link href="css/set1.css" rel="stylesheet"> <!--plugin css do input text -->
    <link href="css/bootstrap-select.min.css" rel="stylesheet"> <!--plugin css do input select -->
    <script src="js/jquery-3.1.1.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/bootbox.min.js"></script> <!--plugin js do okien dialogowych (potwierdzenie) -->
    <script src="js/bootstrap-select.min.js"></script> <!--plugin js do input select -->


</head>
<body>

<div class="navbar navbar-default btn-variants navbar-fixed" role="navigation">
    <div style="text-align: center" class="col-lg-8 col-lg-offset-2 col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3">

      <h2 class="font_logo ">PANEL HOTLINE</b></h2>

    </div>

    <div class="col-lg-2 col-lg-offset-0 col-md-2 col-md-offset-1 col-sm-2 col-sm-offset-1 col-sm-6 col-sm-offset-3 col-xs-4 col-xs-offset-4" >
        <a role="button" class="btn btn-primary btn-sm " style="margin-top: 15px" href="main.php">Powrót</a>
        <a data-toggle="tooltip" data-placement="bottom" title="Zalogowany:<?php  echo "    ".$name." ".$surname; ?>" role="button" class="btn btn-default btn-sm " style="margin-top: 15px" href="logout.php">Wyloguj</a>
    </div>


    </div>
</div>

<div class="container">
    <?php
    echo $_SESSION['alert2'];
    unset($_SESSION['alert2']);
    ?>
<div class="alert alert-info">
    <?php
    echo "<h4>Modyfikujesz stanowiska pracownika: <b>".$tablica[imie]." ".$tablica[nazwisko]."<h4 /></b>";
    ?>

</div>
<button class="btn btn-default col-lg-4 col-lg-offset-4" style="margin-bottom: 15px">Dodaj stanowisko</button>
    <table class="table table-striped" cellspacing='0' style='text-align: center'>
    <tr>
        <th style="text-align: center">lp.</th>
        <th style="text-align: center">Jednostka org.</th>
        <th style="text-align: center">Stanowisko</th>
        <th style="text-align: center">Usuń stanowisko</th>
        <th style="text-align: center">Czy główne</th>

    </tr>

        <?php
        $zapytanie_stanowiska = "SELECT *  FROM pracownicy_stanowiska WHERE id_pracownika='$id' AND status=1";
        $wynik_stanowiska = $db2_hr->query($zapytanie_stanowiska);
        $ilosc_stanowiska = $wynik_stanowiska->num_rows;
        for ($i = 0; $i < $ilosc_stanowiska; $i++) {
            $tablica_stanowiska = $wynik_stanowiska->fetch_assoc();

            echo "<tr><td>".$licznik.".</td>";
            $licznik++;
            $zapytanie_jednostka = "SELECT * FROM jednostki_organizacyjne_ewidencja WHERE id='$tablica_stanowiska[id_jednostki_organizacyjnej]'";
            $wynik_jednostka = $db2->query($zapytanie_jednostka);
            $ilosc_jednostka = $wynik_jednostka->num_rows;
            $tablica_jednostka = $wynik_jednostka->fetch_assoc();


            echo "<td>".$tablica_jednostka['nazwa']."</td>";

            $zapytanie_stanowisko = "SELECT * FROM stanowiska_ewidencja WHERE id='$tablica_stanowiska[id_stanowiska]'";
            $wynik_stanowisko = $db2->query($zapytanie_stanowisko);
            $ilosc_stanowisko = $wynik_stanowisko->num_rows;
            $tablica_stanowisko = $wynik_stanowisko->fetch_assoc();


            echo "<td>".$tablica_stanowisko['nazwa']."</td>";

if ($tablica_stanowiska[czy_glowne]==1){

echo "<td><input disabled type=submit class=\"btn btn-danger disabled\" value='Usuń' onclick=\"bootbox.confirm('Czy chcesz usunąć stanowisko<b> " . $tablica_jednostka['nazwa'] . " | " . $tablica_stanowisko['nazwa'] . "</b>  ?', function(result){ if (result==true) {window.location.href='odblokuj.php?id=" . $tablica['pesel'] . "'}; });\" class=\"myButton2\"></td>
<td>";
    echo "<img src=\"img/tak.png\" width=\"25px\"> </td>";
}
else{
            echo " <td><input type=submit class=\"btn btn-danger\" value='Usuń' onclick=\"bootbox.confirm('Czy chcesz usunąć stanowisko<b> " . $tablica_jednostka['nazwa'] . " | " . $tablica_stanowisko['nazwa'] . "</b>  ?', function(result){ if (result==true) {window.location.href='odblokuj.php?id=" . $tablica['pesel'] . "'}; });\" class=\"myButton2\"></td>
  <td> <input type=submit class=\"btn btn-info\" value='Ustaw' onclick=\"bootbox.confirm('Czy chcesz ustawić stanowisko <b> " . $tablica_jednostka['nazwa'] . " | " . $tablica_stanowisko['nazwa'] . "</b>  jako główne ?', function(result){ if (result==true) {window.location.href='odblokuj.php?id=" . $tablica['pesel'] . "'}; });\" class=\"myButton2\"></td>";

echo"

";
}

                }
             echo "</tr>";
            }

    ?>


</table>
<!-- data-whatever="@mdo"-->





    <?php
    echo "<div>".$_SESSION['alert']."</div>";
    unset($_SESSION['alert']);
    }
    else
    {
        header('location: logout.php');
        exit();
        //jesli pierwszy warunek nie został spełniony to prześlij to strony wylogowania
    }
    //LOGOWANIE - SPRAWDZENIE - STOP
    ?>
</div>

<script src="js/classie.js"></script>
<script>



    $('#exampleModal').on('show.bs.modal', function (event) {
       var button = $(event.relatedTarget) // Button that triggered the modal
       var recipient = button.data('whatever') // Extract info from data-* attributes
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
       var modal = $(this)
       modal.find('.modal-title').text('New message to ' + recipient)
       modal.find('.modal-body input').val(recipient)
   })


    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })


    $(function() {
        // trim polyfill : https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/String/Trim
        if (!String.prototype.trim) {
            (function() {
                // Make sure we trim BOM and NBSP
                var rtrim = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g;
                String.prototype.trim = function() {
                    return this.replace(rtrim, '');
                };
            })();
        }

        [].slice.call( document.querySelectorAll( 'input.input__field' ) ).forEach( function( inputEl ) {
            // in case the input is already filled..
            if( inputEl.value.trim() !== '' ) {
                classie.add( inputEl.parentNode, 'input--filled' );
            }

            // events:
            inputEl.addEventListener( 'focus', onInputFocus );
            inputEl.addEventListener( 'blur', onInputBlur );
        } );

        function onInputFocus( ev ) {
            classie.add( ev.target.parentNode, 'input--filled' );
        }

        function onInputBlur( ev ) {
            if( ev.target.value.trim() === '' ) {
                classie.remove( ev.target.parentNode, 'input--filled' );
            }
        }
    })();
</script>
</body>
</html>