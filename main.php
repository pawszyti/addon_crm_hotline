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
  <div class="col-lg-1 col-lg-offset-1 col-md-2 col-md-offset-1 col-sm-2 col-sm-offset-1 col-sm-6 col-sm-offset-3 col-xs-4 col-xs-offset-4" ><a data-toggle="tooltip" data-placement="bottom" title="Zalogowany:<?php  echo "    ".$name." ".$surname; ?>" role="button" class="btn btn-default btn-sm " style="margin-top: 15px" href="logout.php">Wyloguj</a></div>
</div>




<div class="container">
    <?php
    echo $_SESSION['alert2'];
    unset($_SESSION['alert2']);
    ?>
    <div> <form action='' method="post">


      <div class="col-lg-4">  <span class="input input--hoshi">			<section class="content">
					<input class="input__field input__field--hoshi" type="text" id="input-4" name="imie" autocomplete="off"/>
					<label class="input__label input__label--hoshi input__label--hoshi-color-1" for="input-4">
						<span class="input__label-content input__label-content--hoshi">Imię</span>
					</label>
				</span>
      </div>
        <div class="col-lg-4"> <span class="input input--hoshi">
					<input class="input__field input__field--hoshi" type="text" id="input-4" name="nazwisko" autocomplete="off" />
					<label class="input__label input__label--hoshi input__label--hoshi-color-1" for="input-4">
						<span class="input__label-content input__label-content--hoshi">Nazwisko</span>
					</label>
				</span>
        </div>

        <br /><div class="col-lg-4 col-lg-offset-0 col-md-3 col-md-offset-1 col-sm-4 col-xs-10 col-xs-offset-1""><button type="submit" class="btn btn-default form-control">Wyszukaj</button></div>


    </form>

    </div>

    <table class="table table-striped" cellspacing='0' style='text-align: center'>
    <tr>
        <th style="text-align: center">lp.</th>
        <th style="text-align: center">Imię</th>
        <th style="text-align: center">Nazwisko</th>
        <th style="text-align: center">Jednostka Org.</th>
        <th style="text-align: center">Czy aktywny</th>
        <th style="text-align: center">Odblokuj</th>
        <th style="text-align: center">Przepnij</th>
        <th style="text-align: center">Modyfikuj<br /></th>
    </tr>

    <?php



    if(isset($_POST['imie'])||($_POST['nazwisko'])){

        if(($_POST['imie']=="") && ($_POST['nazwisko']=="")){
        $_SESSION['alert'] = '<div class="alert alert-danger" style="margin-top: 30px">Nic nie wpisano.</div>';}
        else{




        if(($_POST['imie']==''))
        $lp = 0;
        $imie = $_POST['imie'];
        $nazwisko = $_POST['nazwisko'];
        $zapytanie = "SELECT *  FROM jednostki_organizacyjne_ewidencja, uzytkownicy_ewidencja 
                      WHERE jednostki_organizacyjne_ewidencja.id = uzytkownicy_ewidencja.id_jednostki_organizacyjnej AND nazwisko LIKE '$nazwisko%' AND imie LIKE '$imie%'" ;
        if ($wynik = $db2->query($zapytanie)) {
            $ilosc = $wynik->num_rows;
            $licznik = $ilosc;

            if ($ilosc<1){
                $_SESSION['alert'] = '<div class="alert alert-danger" style="margin-top: 30px">Nie znaleziono pasującego użytkownika w systemie.</div>';

            }
            else
                {



            for ($i = 0; $i < $ilosc; $i++) {
                $tablica = $wynik->fetch_assoc();
                $pesel = $tablica['pesel'];

                $zapytanie_hr = "SELECT * FROM pracownicy_ewidencja WHERE pesel LIKE '$pesel'";
                $wynik_hr = $db2_hr->query($zapytanie_hr);
                $tablica_hr = $wynik_hr->fetch_assoc();
                $pracuje = $tablica_hr['czy_pracuje'];
                if ($pracuje == 0) {
                    continue;
                }
                $limit = $limit + 1;
                if ($limit == 26) {
                    $_SESSION['alert'] = '<div class="alert alert-danger">Znaleziono więcej niż 25 wyników, zawęź kryteria wyszukiwania.</div>';
                    break;

                }
                $lp = $lp + 1;
                echo "
            <tr>
        <td>" . $lp . "</td>
        <td>" . $tablica['imie'] . "</td>
        <td>" . $tablica['nazwisko'] . "</td>
        <td>" . $tablica['nazwa'] . "</td>
        <td>";

                if ($tablica['status'] == 1) {
                    echo "<img src=\"img/ok.png\" width='25px'>";
                } else {
                    echo "<img src=\"img/no.png\" width='25px'>";
                }


                echo "</td>";


                echo "<td><input type=submit class=\"btn btn-danger\" value='Odblokuj' onclick=\"bootbox.confirm('Czy chcesz odblokować użytkownika<b> " . $tablica['imie'] . " " . $tablica['nazwisko'] . "</b>  zmienić jego hasło na PESEL?', function(result){ if (result==true) {window.location.href='odblokuj.php?id=" . $tablica['pesel'] . "'}; });\" class=\"myButton2\"></td>";

                echo "<td><input type=submit class=\"btn btn-warning\" value='Przepnij' data-toggle=\"modal\" class=\"myButton2\" data-target=\"#exampleModal" . $tablica[id] . "\">
                
                <td><a class=\"btn btn-success \" href='modyfikuj.php?id=".$tablica[id]."' \">Modyfikuj</a>
                </td>";


                echo "        <div class=\"modal fade\" id=\"exampleModal" . $tablica[id] . "\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"exampleModalLabel\">
    <div class=\"modal-dialog\" role=\"document\">
        <div class=\"modal-content\">
            <div class=\"modal-header\">
                <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>
                <h4 id=\"exampleModalLabel\">Wybierz oddział docelowy</h4>
            </div>
            <div class=\"modal-body\">
                <form action=\"przepnij.php\" method='post' name='" . $tablica[id] . "'>  
                       
               <select name='oddzial'>
               <option value ='0'>- - wybierz oddzial- -</option>
               ";


                $zapytanie_oddzialy = "SELECT * FROM jednostki_organizacyjne_ewidencja WHERE ((id<799 OR id>1399) AND (id<16999 OR id=20000)) AND (status=1) AND (nazwa!='a') ORDER BY nazwa ";
                $wynik_oddzialy = $db2->query($zapytanie_oddzialy);
                $ilosc_oddzialy = $wynik_oddzialy->num_rows;
                for ($b = 0; $b < $ilosc_oddzialy; $b++) {
                $tablica_oddzialy = $wynik_oddzialy->fetch_assoc();


        echo " 
                <option value = '".$tablica_oddzialy[id]."' > ".$tablica_oddzialy[nazwa]." </option > ";
               }

echo " </select > <select name='stanowisko' class='selectpicker' data-live-search='true'>
                <option value ='0' >- - wybierz stanowisko- -</option>

 ";
  $zapytanie_stanowiska = "SELECT * FROM stanowiska_ewidencja WHERE status=1 AND ID!=215 AND nazwa!='ppppp' AND usunieto=0 ORDER BY nazwa ";
                $wynik_stanowiska = $db2->query($zapytanie_stanowiska);
               $ilosc_stanowiska = $wynik_stanowiska->num_rows;
                for ($c = 0; $c < $ilosc_stanowiska; $c++) {
                    $tablica_stanowiska = $wynik_stanowiska->fetch_assoc();

                    echo " 
                <option value = '".$tablica_stanowiska[id]."' > ".$tablica_stanowiska[nazwa]." </option >
               ";

                }


echo "<select />
<input type='hidden' value='".$tablica[id]."' name='id'>
               
            </div>
            <div class=\"modal-footer\">
                <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Anuluj</button>
                <button type=\"submit\" class=\"btn btn-warning\">Przepnij</button>
                </form>
            </div>
        </div>
    </div>
</div>";



                echo "</tr>";
            }

                    if ($lp==0){
                        $_SESSION['alert'] = '<div class="alert alert-danger" style="margin-top: 30px">Nie znaleziono pasującego użytkownika w systemie.</div>';

                    }


                }
        } else
            echo "error";
    }



    }
    else{
        echo "<div class=\"alert alert-warning\" style='margin-top: 65px'>Aby wyświetlić liste wyszukaj pracowników</div>";
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