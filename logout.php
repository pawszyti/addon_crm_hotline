<?php
session_start();
//WYLOGOWYWANIE UŻYTKOWNIKA
session_destroy();
//niszczenie sesji i przekierowanie do index.php
header('location: index.php');
