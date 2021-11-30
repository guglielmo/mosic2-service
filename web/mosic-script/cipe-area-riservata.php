
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

setlocale(LC_CTYPE, 'it_IT');

file_put_contents("debug-legge.txt", "test fino qui" );


require_once "config-web.php";


include_once('mosic-script/function.php');
// imposto il time limit dello script a 2 ore
set_time_limit(7200);


function invioFile ($token, $file, $targetInvioFile) {
    $headers = [
        'Accept: */*',
        'Content-Type: multipart/form-data',
        'Content-Disposition: form-data; name=\"'.$file.'\"',
        'Cache-Control: no-cache',
        'Authorization: JWT ' . $token
    ];

    $target = $targetInvioFile . $file;

    $post = new \CURLFile($file, 'application/pdf', $file);



    if (file_exists($file)) {
        $data_array = array(
            'file' => new \CurlFile($file)
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL,$target);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_array);
        curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result=curl_exec ($ch);
        $result_info =curl_getinfo($ch);
    } else {
        $result_info = ['http_code' => 404];
    }


    return $result_info;
}


// ######################### LOGIN


$ch = curl_init();
$fields = array("username"=>"mosic", "password" => "cowpony-butter-vizor");


curl_setopt($ch, CURLOPT_URL,$targetLogin);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));

// receive server response ...
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec ($ch);
$info = curl_getinfo($ch);


curl_close ($ch);


// further processing ....
if ($info['http_code'] == 200) {
    //aggiorniamo lo stato del cipe (nel db)
    aggiornaStatoCipe($argv[1], "Login avvenuta con successo.");
} else {
    aggiornaStatoCipe($argv[1], "Errore nella login.");
    exit;
}

$token =json_decode($server_output)->token;




//######### chiamo l'api per prendere l'url dell'area riservata

$ch = curl_init();
$headers = [
    'Accept: */*',
    'Cache-Control: no-cache',
    'Authorization: JWT ' . $token
];

curl_setopt($ch, CURLOPT_URL,$targetGetUrlCipe . $argv[1]);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, "");

// receive server response ...
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec($ch);
$info = curl_getinfo($ch);



curl_close ($ch);

$urlRiservata =json_decode($server_output)->url;
$idRiservata =json_decode($server_output)->id;


//if ($info['http_code'] == 200) {
    //aggiorniamo lo stato del cipe (nel db)
    aggiornaURLCipe($argv[1], $urlRiservata);
//} else {
    //aggiornaStatoCipe($argv[1], "Errore nella get dell'url dell'area riservata del cipe.");
//}

//file_put_contents("debug-area-riservata-cipe.txt", $server_output);



// ######################### DELETE
$ch = curl_init();
$headers = [
    'Accept: */*',
    'Cache-Control: no-cache',
    'Content-Type: application/json',
    'Authorization: JWT ' . $token
];

curl_setopt($ch, CURLOPT_URL,$targetDeleteCipe . $idRiservata);
//curl_setopt($ch, CURLOPT_URL,"http://area-riservata.mosic2.celata.com/cipe/144");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// receive server response ...
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec ($ch);
$info = curl_getinfo($ch);

curl_close ($ch);

// further processing ....
if ($info['http_code'] == 204) {
    //aggiorniamo lo stato del cipe (nel db)
    aggiornaStatoCipe($argv[1], "Dati Cipe eliminati con successo.");
    aggiornaURLCipe($argv[1], "");
} else {
    aggiornaStatoCipe($argv[1], "Errore nella rimozione del cipe.");
    //exit;
}



// ######################### INVIO METADATI

$ch = curl_init();
$headers = [
    'Accept: */*',
    'Cache-Control: no-cache',
    'Content-Type: application/json',
    'Authorization: JWT ' . $token
];

//$content = array("username"=>"mosic", "password" => "cowpony-butter-vizor");
curl_setopt($ch, CURLOPT_URL,$targetInvioMetadatiCipe);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $argv[2]);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// receive server response ...
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec ($ch);
$info = curl_getinfo($ch);

curl_close ($ch);

file_put_contents("debug-invio-metadati-json.txt", $argv[2]);
file_put_contents("debug-invio-metadati.txt", $server_output);

//echo "aaa"; exit;

// further processing ....
if ($info['http_code'] == 201) {
    //aggiorniamo lo stato del cipe (nel db)
    aggiornaStatoCipe($argv[1], "Invio dei metadati effettuata con successo.");
} else {
    aggiornaStatoCipe($argv[1], "Errore nell'invio dei metadati");
    exit;
}




//######### chiamo l'api per prendere l'url dell'area riservata

$ch = curl_init();
$headers = [
    'Accept: */*',
    'Cache-Control: no-cache',
    'Authorization: JWT ' . $token
];

curl_setopt($ch, CURLOPT_URL,$targetGetUrlCipe . $argv[1]);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, "");

// receive server response ...
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec ($ch);
$info = curl_getinfo($ch);
//file_put_contents("debug-area-riservata-cipe.txt", $info);

curl_close ($ch);

$urlRiservata =json_decode($server_output)->url;
$idRiservata =json_decode($server_output)->id;


if ($info['http_code'] == 200) {
    //aggiorniamo lo stato del cipe (nel db)
    aggiornaURLCipe($argv[1], $urlRiservata);
} else {
    aggiornaStatoCipe($argv[1], "Errore nella get dell'url dell'area riservata del cipe.");
    exit;
}



$cipeTemp = json_decode($argv[2]);



file_put_contents("debug-area-riservata.txt", "test");


//#########
//######### invio fisicamente i files
//#########
$numero_file = 0;
$numero_file_caricati = 0;
$current_file = file_get_contents("file-errori-upload-cipe.txt");
//conto gli allegati
foreach ($cipeTemp->punti_odg as $i => $v) {
    foreach ($v->allegati as $item => $k) {
        $numero_file++;
    }
}
file_put_contents("debug-numero-file.txt", $numero_file);

$array_file_errati = [];
foreach ($cipeTemp->punti_odg as $i => $v) {
    foreach ($v->allegati as $item => $k) {

        //invia fisicamente il file (EXEC)
        $result_info = invioFile($token, $k->relURI, $targetInvioFile);
        file_put_contents("file-result-invio-file.txt", $result_info['http_code']);

        //Aggiorno lo stato del cipe
        if ($result_info['http_code'] != 204 && $result_info['http_code'] != 100) {
            $current_file .= $argv[1] . " -------> dimensione: " . (($k->dimensione / 1000) / 1000) . "Mb --- " . $k->relURI . " --code--> " . $result_info['http_code'] . "\n";
            file_put_contents("file-errori-upload-cipe.txt", $current_file);
            $array_file_errati[] = $k->relURI;
        } else {
            $current_file .= $argv[1] . " -------> dimensione: " . (($k->dimensione / 1000) / 1000) . "Mb --- " . $k->relURI . " --code--> " . $result_info['http_code'] . "\n";
            //file_put_contents("file-errori-upload-cipe.txt", $current_file);

            $numero_file_caricati++;
            aggiornaStatoCipe($argv[1], "Caricati " .$numero_file_caricati. " file di " . $numero_file . " (".$numero_file_caricati.",".$numero_file.")");
        }
        curl_close ($ch);
    }
}

$current_file .=  "\n\n";
file_put_contents("file-errori-upload-cipe.txt", $current_file);


//se ho inviato tutti i file correttamente
if ($numero_file == $numero_file_caricati) {
    aggiornaStatoCipe($argv[1], "Pubblicazione completata - Tutti i file caricati (".$numero_file_caricati." di ".$numero_file.")" );
    aggiornaUfficialeRiunioneCipe($argv[1],1);
} else {
    if ($numero_file_caricati > 0) {
        aggiornaStatoCipe($argv[1],  "Pubblicazione parziale - Caricati ".$numero_file_caricati." file di " . $numero_file . " (".$numero_file_caricati.",".$numero_file.")");
        aggiornaUfficialeRiunioneCipe($argv[1],1);
    } else {
        aggiornaStatoCipe($argv[1],  "Pubblicazione parziale - Nessun file trovato di " . $numero_file . " (".$numero_file_caricati.",".$numero_file.")");
        aggiornaUfficialeRiunioneCipe($argv[1],1);
    }



    //aggiornaStatoCipe($argv[1],  "Errore procedura - Caricati ".$numero_file_caricati." file di " . $numero_file . " (".$numero_file_caricati.",".$numero_file.")");

//    if (count($array_file_errati > 0)) {
//        $file_ricaricati = 0;
//
//        for ($i=0; $i<1; $i++) { //riprovo x volte
//            foreach ($array_file_errati as $key => $value) {
//                //invia fisicamente il file (EXEC)
//                $result_info = invioFile($token, $value, $targetInvioFile);
//
//                //Aggiorno lo stato del cipe
//                if ($result_info['http_code'] != 204 && $result_info['http_code'] != 100) {
//                    $current_file .= $argv[1] . " ---tentativo----> " . $value . " --code--> " . $result_info['http_code'] . "\n";
//                    //file_put_contents("file-errori-upload-cipe.txt", $current_file);
//                    aggiornaStatoCipe($argv[1], "Procedura in corso - Caricati " . $numero_file_caricati . " file di " . $numero_file . " - tentativi " . $i . " (".$numero_file_caricati.",".$numero_file.")");
//                } else {
//                    aggiornaStatoCipe($argv[1], "Procedura in corso - Caricati " . $numero_file_caricati . " file di " . $numero_file . " - tentativi " . $i . " (".$numero_file_caricati.",".$numero_file.")");
//                    $file_ricaricati++;
//                }
//                curl_close($ch);
//            }
//        }
//
//        if ($file_ricaricati > 0) {
//            aggiornaStatoCipe($argv[1], "Procedura conclusa - Caricati ". ($numero_file_caricati + $file_ricaricati) ." file di " . $numero_file . " (".$numero_file_caricati.",".$numero_file.")");
//        } else {
//            aggiornaStatoCipe($argv[1], "Errore procedura - Caricati ".($numero_file_caricati + $file_ricaricati) ." file di " . $numero_file . " (".$numero_file_caricati.",".$numero_file.")");
//        }
//    }
}






echo "-END-";
// chiusura della connessione
$db->close();
?>

</body>
