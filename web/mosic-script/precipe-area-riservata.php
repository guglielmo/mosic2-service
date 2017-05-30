
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





//file_put_contents("file-errori-upload.txt", ini_get('upload_max_filesize'));
//exit;

function invioFile ($token, $file) {
    $headers = [
        'Accept: */*',
        'Content-Type: multipart/form-data',
        'Content-Disposition: form-data; name=\"'.$file.'\"',
        'Cache-Control: no-cache',
        'Authorization: JWT ' . $token
    ];

    $target = 'http://area-riservata.mosic2.celata.com/upload_file/'. $file;

    $post = new \CURLFile($file, 'application/pdf', $file);

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
    return $result_info;
}











// ######################### LOGIN

$ch = curl_init();
$fields = array("username"=>"mosic", "password" => "cowpony-butter-vizor");

curl_setopt($ch, CURLOPT_URL,"http://area-riservata.mosic2.celata.com/api-token-auth/");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));

// receive server response ...
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec ($ch);
$info = curl_getinfo($ch);

curl_close ($ch);

// further processing ....
if ($info['http_code'] == 200) {
    //aggiorniamo lo stato del precipe (nel db)
    aggiornaStato($argv[1], "Login avvenuta con successo.");
} else {
    aggiornaStato($argv[1], "Errore nella login.");
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

curl_setopt($ch, CURLOPT_URL,"http://area-riservata.mosic2.celata.com/seduta/precipe/" . $argv[1]);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, "");

// receive server response ...
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec ($ch);
$info = curl_getinfo($ch);

curl_close ($ch);

$urlRiservata =json_decode($server_output)->url;
$idRiservata =json_decode($server_output)->id;


if ($info['http_code'] == 200) {
    //aggiorniamo lo stato del precipe (nel db)
    aggiornaURL($argv[1], $urlRiservata);
} else {
    //aggiornaStato($argv[1], "Errore nella get dell'url dell'area riservata del precipe.");
}




// ######################### DELETE
$ch = curl_init();
$headers = [
    'Accept: */*',
    'Cache-Control: no-cache',
    'Content-Type: application/json',
    'Authorization: JWT ' . $token
];

curl_setopt($ch, CURLOPT_URL,"http://area-riservata.mosic2.celata.com/precipe/" . $idRiservata);
//curl_setopt($ch, CURLOPT_URL,"http://area-riservata.mosic2.celata.com/precipe/144");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// receive server response ...
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec ($ch);
$info = curl_getinfo($ch);

curl_close ($ch);

// further processing ....
if ($info['http_code'] == 204) {
    //aggiorniamo lo stato del precipe (nel db)
    aggiornaStato($argv[1], "Dati Precipe eliminati con successo.");
    aggiornaURL($argv[1], "");
} else {
    aggiornaStato($argv[1], "Errore nella rimozione del precipe.");
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
curl_setopt($ch, CURLOPT_URL,"http://area-riservata.mosic2.celata.com/precipe");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $argv[2]);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// receive server response ...
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec ($ch);
$info = curl_getinfo($ch);

curl_close ($ch);

// further processing ....
if ($info['http_code'] == 201) {
    //aggiorniamo lo stato del precipe (nel db)
    aggiornaStato($argv[1], "Invio dei metadati effettuata con successo.");
} else {
    aggiornaStato($argv[1], "Errore nell'invio dei metadati");
}



//######### chiamo l'api per prendere l'url dell'area riservata

$ch = curl_init();
$headers = [
    'Accept: */*',
    'Cache-Control: no-cache',
    'Authorization: JWT ' . $token
];

curl_setopt($ch, CURLOPT_URL,"http://area-riservata.mosic2.celata.com/seduta/precipe/" . $argv[1]);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, "");

// receive server response ...
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec ($ch);
$info = curl_getinfo($ch);

curl_close ($ch);

$urlRiservata =json_decode($server_output)->url;
$idRiservata =json_decode($server_output)->id;


if ($info['http_code'] == 200) {
    //aggiorniamo lo stato del precipe (nel db)
    aggiornaURL($argv[1], $urlRiservata);
} else {
    aggiornaStato($argv[1], "Errore nella get dell'url dell'area riservata del precipe.");
}



$precipeTemp = json_decode($argv[2]);

//#########
//######### invio fisicamente i files
//#########
$numero_file = 0;
$numero_file_caricati = 0;
$current_file = file_get_contents("file-errori-upload.txt");
//conto gli allegati
foreach ($precipeTemp->punti_odg as $i => $v) {
    foreach ($v->allegati as $item => $k) {
            $numero_file++;
    }
}


$array_file_errati = "";
foreach ($precipeTemp->punti_odg as $i => $v) {
    foreach ($v->allegati as $item => $k) {

        //invia fisicamente il file (EXEC)
        $result_info = invioFile($token, $k->relURI);

        //Aggiorno lo stato del precipe
        if ($result_info['http_code'] != 204) {
            $current_file .= $argv[1] . " -------> " . $k->relURI . " --code--> " . $result_info['http_code'] . "\n";
            file_put_contents("file-errori-upload.txt", $current_file);
            $array_file_errati[] = $k->relURI;
        } else {
            $current_file .= $argv[1] . " -------> " . $k->relURI . " --code--> " . $result_info['http_code'] . "\n";
            file_put_contents("file-errori-upload.txt", $current_file);

            $numero_file_caricati++;
            aggiornaStato($argv[1], "Caricati " .$numero_file_caricati. " file di " . $numero_file . " (".$numero_file_caricati.",".$numero_file.")");
        }
        curl_close ($ch);
    }
}

$current_file .=  "\n\n";
file_put_contents("file-errori-upload.txt", $current_file);


//se ho inviato tutti i file correttamente
if ($numero_file == $numero_file_caricati) {
    aggiornaStato($argv[1], "Procedura conclusa - Caricati ".$numero_file_caricati." file di " . $numero_file . " (".$numero_file_caricati.",".$numero_file.")" );
} else {
    aggiornaStato($argv[1],  "Errore procedura - Caricati ".$numero_file_caricati." file di " . $numero_file . " (".$numero_file_caricati.",".$numero_file.")");

    if (count($array_file_errati > 0)) {
        $file_ricaricati = 0;

        for ($i=0; $i<1; $i++) { //riprovo x volte
            foreach ($array_file_errati as $key => $value) {
                //invia fisicamente il file (EXEC)
                $result_info = invioFile($token, $value);

                //Aggiorno lo stato del precipe
                if ($result_info['http_code'] != 204) {
                    $current_file .= $argv[1] . " ---tentativo----> " . $value . " --code--> " . $result_info['http_code'] . "\n";
                    file_put_contents("file-errori-upload.txt", $current_file);
                    aggiornaStato($argv[1], "Procedura in corso - Caricati " . $numero_file_caricati . " file di " . $numero_file . " - tentativi " . $i . " (".$numero_file_caricati.",".$numero_file.")");
                } else {
                    aggiornaStato($argv[1], "Procedura in corso - Caricati " . $numero_file_caricati . " file di " . $numero_file . " - tentativi " . $i . " (".$numero_file_caricati.",".$numero_file.")");
                    $file_ricaricati++;
                }
                curl_close($ch);
            }
        }

        if ($file_ricaricati > 0) {
            aggiornaStato($argv[1], "Procedura conclusa - Caricati ". ($numero_file_caricati + $file_ricaricati) ." file di " . $numero_file . " (".$numero_file_caricati.",".$numero_file.")");
        } else {
            aggiornaStato($argv[1], "Errore procedura - Caricati ".($numero_file_caricati + $file_ricaricati) ." file di " . $numero_file . " (".$numero_file_caricati.",".$numero_file.")");
        }

    }
}






echo "-END-";
// chiusura della connessione
$db->close();
?>

</body>
