<?php

$sqlname='localhost';
$username='mosic_symfony';
$password='_um3c1pdf_';
$db='mosic_symfony';

$db= mysqli_connect("$sqlname", "$username","$password","$db") or die(mysqli_error());
if (mysqli_connect_errno($db)) {
    echo "Errore in connessione al DB: ".mysqli_connect_error();
}

// imposto il time limit dello script a 2 ore
set_time_limit(7200);

$filePath = "service/";

