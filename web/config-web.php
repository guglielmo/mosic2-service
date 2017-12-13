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



$filePathSanitize = "";
$filePath = "";

// PATH PER AREA RISERVATA
// $area_ris_base_url = "http://area-riservata.mosic2.celata.com";
$area_ris_base_url = "http://area-riservata.mosic2.celata.com";
$targetInvioFile = $area_ris_base_url . "/upload_file/";
$targetLogin = $area_ris_base_url . "/api-token-auth/";

$targetGetUrlPreCipe = $area_ris_base_url . "/seduta/precipe/";
$targetGetUrlCipe = $area_ris_base_url . "/seduta/cipe/";

$targetDeletePreCipe = $area_ris_base_url . "/precipe/";
$targetDeleteCipe = $area_ris_base_url . "/cipe/";

$targetInvioMetadatiPreCipe = $area_ris_base_url . "/precipe";
$targetInvioMetadatiCipe = $area_ris_base_url . "/cipe";
