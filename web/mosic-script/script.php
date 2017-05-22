
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
</head>
<body>

<?php

//setup php for working with Unicode data
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
mb_http_input('UTF-8');
mb_language('uni');
mb_regex_encoding('UTF-8');
ob_start('mb_output_handler');



// DB INFO
$indirizzoHost = "localhost";
$nomeUser = "mosic_symfony";
$password = "_um3c1pdf_";
$nomeDb= "mosic_symfony";

//CONNESSIONE AL DB
$db = mysqli_connect( $indirizzoHost, $nomeUser, $password, $nomeDb);
mysqli_set_charset($db,"utf8");

if (mysqli_connect_errno($db)) {      
    echo "Errore in connessione al DB: ".mysqli_connect_error(); 
}

error_reporting(0);

include_once('function.php');
// imposto il time limit dello script a 2 ore
set_time_limit(7200);


//setTableRegistri();
//setOggettoRegistri();
//setMittentiRegistri();
//updateIdTitolari();
//gestioneFilesRegistri(); //+ sottofasicoli

//gestioneFilesPreCipe();
//gestioneFilesCipe();

//setSottofascicoliDenominazioneSuRegistri();

//setIdFascicoli();

//### da eseguire una sola volta! (serve per popolare la tabella msc_precipe)
//raggruppaPreCipeData();
//setOrdiniPreCipe();
//setRegistriPrecipe();


//raggruppaCipeData();
//setOrdiniCipe();
//setRegistriCipe();

//setUfficiPreCipeOdg();
//setUfficiCipeOdg();

//renameFile();

//setDelibere();
//setUfficiDelibere();
//setAllegatiDelibere(); //da finire,
//setFunzionariDelibere();
//setCorteDeiContiDelibere();

//setTipoRegistrazioneCC(); //temporanea fix

//setDateDelibereGiorni();

//setUtentiAdempimenti();


echo "-END-";
// chiusura della connessione
$db->close();
?>

</body>
