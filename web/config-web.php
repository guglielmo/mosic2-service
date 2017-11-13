<?php

$sqlname='localhost';
$username='mosic_symfony';
$password='_um3c1pdf_';
$db='mosic_symfony';

$db= mysqli_connect("$sqlname", "$username","$password","$db") or die(mysqli_error());
mysqli_set_charset($db,"utf8");

if (mysqli_connect_errno($db)) {
    echo "Errore in connessione al DB: ".mysqli_connect_error();
}

// imposto il time limit dello script a 2 ore
set_time_limit(7200);



//$filePath = "service/";
$filePath = "";

// PATH PER AREA RISERVATA
$targetInvioFile = "http://area-riservata.mosic2.celata.com/upload_file/";
$targetLogin = "http://area-riservata.mosic2.celata.com/api-token-auth/";

$targetGetUrlPreCipe = "http://area-riservata.mosic2.celata.com/seduta/precipe/";
$targetGetUrlCipe = "http://area-riservata.mosic2.celata.com/seduta/cipe/";

$targetDeletePreCipe = "http://area-riservata.mosic2.celata.com/precipe/";
$targetDeleteCipe = "http://area-riservata.mosic2.celata.com/cipe/";

$targetInvioMetadatiPreCipe = "http://area-riservata.mosic2.celata.com/precipe";
$targetInvioMetadatiCipe = "http://area-riservata.mosic2.celata.com/cipe";