<?php

$sqlname='localhost';
$username='mosicmis_mosic';
$password='!d]txfMhpecD';
$db='mosicmis_mosic';

$db= mysqli_connect("$sqlname", "$username","$password","$db") or die(mysqli_error());
mysqli_set_charset($db,"utf8");

if (mysqli_connect_errno($db)) {
    echo "Errore in connessione al DB: ".mysqli_connect_error();
}

// imposto il time limit dello script a 2 ore
set_time_limit(7200);



//$filePath = "service/";
$filePath = "";



//// PATH PER AREA RISERVATA
//$targetInvioFile = "http://area-riservata.mosic2.celata.com/upload_file/";
//$targetLogin = "http://area-riservata.mosic2.celata.com/api-token-auth/";
//
//$targetGetUrlPreCipe = "http://area-riservata.mosic2.celata.com/seduta/precipe/";
//$targetGetUrlCipe = "http://area-riservata.mosic2.celata.com/seduta/cipe/";
//
//$targetDeletePreCipe = "http://area-riservata.mosic2.celata.com/precipe/";
//$targetDeleteCipe = "http://area-riservata.mosic2.celata.com/cipe/";
//
//$targetInvioMetadatiPreCipe = "http://area-riservata.mosic2.celata.com/precipe";
//$targetInvioMetadatiCipe = "http://area-riservata.mosic2.celata.com/cipe";






// PATH PER AREA RISERVATA
$targetInvioFile = "http://area-riservata.programmazioneeconomica.gov.it/upload_file/";
$targetLogin     = "http://area-riservata.programmazioneeconomica.gov.it/api-token-auth/";

$targetGetUrlPreCipe = "http://area-riservata.programmazioneeconomica.gov.it/seduta/precipe/";
$targetGetUrlCipe = "http://area-riservata.programmazioneeconomica.gov.it/seduta/cipe/";

$targetDeletePreCipe = "http://area-riservata.programmazioneeconomica.gov.it/precipe/";
$targetDeleteCipe = "http://area-riservata.programmazioneeconomica.gov.it/cipe/";

$targetInvioMetadatiPreCipe = "http://area-riservata.programmazioneeconomica.gov.it/precipe";
$targetInvioMetadatiCipe = "http://area-riservata.programmazioneeconomica.gov.it/cipe";




