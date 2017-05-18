<?php



function setTipoRegistrazioneCC() {
    global $db;

    $query = "SELECT * 
              FROM TABLE_delibere_full";
    $res = mysqli_query($db, $query);
    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {
            if ($row['Tipo_Registrazione'] != "") {
                $query2 = 'UPDATE `msc_delibere` SET `tipo_registrazione_cc`= "' . $row['Tipo_Registrazione'] . '" WHERE id = ' . $row['Codice_Delibera'];

                echo $query2 . "<br>";
                //$res2 = mysqli_query($db, $query2);
            }
        }
    }
}


function setCorteDeiContiDelibere()
{
    global $db;

    $query = "SELECT * 
              FROM TABLE_delibere_full";
    $res = mysqli_query($db, $query);

    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {

            if ($row['Data_RilievoCC'] != "") {
//                $array_temp = explode("/", $row['Data_RilievoCC']);
//                $data_ok = $array_temp[2] . "-" . $array_temp[1] . "-" . $array_temp[0];
//                $query2 = 'UPDATE `TABLE_delibere_full` SET `Data_RilievoCC`= "' . $data_ok . '"  WHERE Codice_Delibera = "' . $row['Codice_Delibera'] . '"';
//                echo $query2 . "<br>";
//                $res2 = mysqli_query($db, $query2);
                $query2 = 'INSERT INTO msc_delibere_cc SET id_delibere = "' . $row['Codice_Delibera'] . '", 
                                                           tipo_documento = "' . $row['Tipo_DocumentoCC'] . '",
                                                           data_rilievo = "' . $row['Data_RilievoCC'] . '",
                                                           numero_rilievo = "' . $row['Numero_RilievoCC'] . '",
                                                           data_risposta = "' . $row['Data_RispostaCC'] . '",
                                                           numero_risposta = "' . $row['Numero_RispostaCC'] . '",
                                                           giorni_rilievo = "' . $row['Giorni_RilievoCC'] . '",
                                                           tipo_rilievo = "' . $row['Tipo_Rilievo'] . '",
                                                           note_rilievo = "' . $row['Nota_RilievoCC'] . '"
                ';
                echo $query2 . "<br>";
                $res2 = mysqli_query($db, $query2);
            }

            if ($row['Data_RilievoCC2'] != "") {
                $query2 = 'INSERT INTO msc_delibere_cc SET id_delibere = "' . $row['Codice_Delibera'] . '", 
                                                           tipo_documento = "' . $row['Tipo_DocumentoCC2'] . '",
                                                           data_rilievo = "' . $row['Data_RilievoCC2'] . '",
                                                           numero_rilievo = "' . $row['Numero_RilievoCC2'] . '",
                                                           data_risposta = "' . $row['Data_RispostaCC2'] . '",
                                                           numero_risposta = "' . $row['Numero_RispostaCC2'] . '",
                                                           giorni_rilievo = "' . $row['Giorni_RilievoCC2'] . '",
                                                           tipo_rilievo = "' . $row['Tipo_Rilievo2'] . '",
                                                           note_rilievo = "' . $row['Nota_RilievoCC2'] . '"
                ';
                echo $query2 . "<br>";
                $res2 = mysqli_query($db, $query2);
            }

            if ($row['Data_RilievoCC3'] != "") {
                $query2 = 'INSERT INTO msc_delibere_cc SET id_delibere = "' . $row['Codice_Delibera'] . '", 
                                                           tipo_documento = "' . $row['Tipo_DocumentoCC3'] . '",
                                                           data_rilievo = "' . $row['Data_RilievoCC3'] . '",
                                                           numero_rilievo = "' . $row['Numero_RilievoCC3'] . '",
                                                           data_risposta = "' . $row['Data_RispostaCC3'] . '",
                                                           numero_risposta = "' . $row['Numero_RispostaCC3'] . '",
                                                           giorni_rilievo = "' . $row['Giorni_RilievoCC3'] . '",
                                                           tipo_rilievo = "' . $row['Tipo_Rilievo3'] . '",
                                                           note_rilievo = "' . $row['Nota_RilievoCC3'] . '"
                ';
                echo $query2 . "<br>";
                $res2 = mysqli_query($db, $query2);
            }

        }
    }
}

function setUfficiDelibere()
{
    global $db;

    $query = "SELECT * 
              FROM TABLE_delibere_full";
    $res = mysqli_query($db, $query);

    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {

            if ($row['Codice_Ufficio'] != "") {
                $query2 = 'INSERT INTO msc_rel_uffici_delibere SET id_delibere = "' . $row['Codice_Delibera'] . '", id_uffici = "' . $row['Codice_Ufficio'] . '" ';
                echo $query2 . "<br>";
                $res2 = mysqli_query($db, $query2);
            }

            if ($row['Codice_Ufficio2'] != "") {
                $query2 = 'INSERT INTO msc_rel_uffici_delibere SET id_delibere = "' . $row['Codice_Delibera'] . '", id_uffici = "' . $row['Codice_Ufficio2'] . '" ';
                echo $query2 . "<br>";
                $res2 = mysqli_query($db, $query2);
            }

            if ($row['Codice_Ufficio3'] != "") {
                $query2 = 'INSERT INTO msc_rel_uffici_delibere SET id_delibere = "' . $row['Codice_Delibera'] . '", id_uffici = "' . $row['Codice_Ufficio3'] . '" ';
                echo $query2 . "<br>";
                $res2 = mysqli_query($db, $query2);
            }

        }
    }
}

function setAllegatiDelibere()
{
    global $db;
    $path = "../files/DELIBERE/per-anno";

    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path)) as $filename) {
        if (!is_dir($filename)) {
            $filename = str_replace("../", "", $filename);
            $explod_path = explode("/", $filename);

            $anno = $explod_path[3];
            $numero = (int) substr($explod_path[4],3,7);
            $tipo = "";

            //se inizia per E
            if (substr($explod_path[4], 0, 1) == "E") {

                //ricavo la delibera ad ANNO e NUMERO
                $query = "SELECT * FROM msc_delibere WHERE YEAR(data) = '$anno' AND numero = '$numero'";
                $res = mysqli_query($db, $query);
                $idDelibera = "non trovato";
                if (mysqli_num_rows($res) >= 1) {
                    while ($row = mysqli_fetch_array($res)) {
                        $idDelibera = $row['id'];
                    }
                }

                if (in_array("versioni", $explod_path)) {//versioni

                    //echo $filename . " ---> ".$explod_path[4]. " ----- anno: " .$anno. " ----- numero: " . $numero ." ----- Delibera: " .$idDelibera. "<br>";

                } else {//principale
                    $nomeFile = $explod_path[4];
                    if (strpos($nomeFile, 'lleg') !== false) {
                       $tipo = "ALL";
                    } else {
                        $tipo = "DEL";
                    }

                    echo $filename . " ------>>>> " . $explod_path[4] . " ----- anno: " . $anno . " ----- numero: " . $numero . " ----- Delibera: " . $idDelibera . " ----- " .$tipo. "<br>";


                    $query = "INSERT INTO `msc_allegati`(`data`, `file`) VALUES (NOW(),'". $filename ."')";
                    echo $query . "<br>";
                    $res2 = mysqli_query($db, $query);

                    $last_id = mysqli_insert_id($db); //ultimo id inserito

                    $query2 = "INSERT INTO `msc_rel_allegati_delibere`(`id_delibere`, `id_allegati`, `tipo`) VALUES ($idDelibera, $last_id, '$tipo')";
                    echo $query2 . "<br>";
                    $res2 = mysqli_query($db, $query2);



                }




            }

        }
    }







//    $query = "SELECT *
//              FROM TABLE_delibere_full";
//    $res = mysqli_query($db, $query);
//
//    if (mysqli_num_rows($res) >= 1) {
//        while ($row = mysqli_fetch_array($res)) {
//
//            if ($row['Allegato_1'] != "") {
//                $query2 = 'INSERT INTO msc_rel_allegati_delibere SET id_delibere = "' . $row['Codice_Delibera'] . '", id_allegati = "' . $row['Allegato_1'] . '" ';
//                echo $query2 . "<br>";
//                $res2 = mysqli_query($db, $query2);
//            }
//
//            if ($row['Allegato_2'] != "") {
//                $query2 = 'INSERT INTO msc_rel_allegati_delibere SET id_delibere = "' . $row['Codice_Delibera'] . '", id_allegati = "' . $row['Allegato_2'] . '" ';
//                echo $query2 . "<br>";
//                $res2 = mysqli_query($db, $query2);
//            }
//
//            if ($row['Allegato_3'] != "") {
//                $query2 = 'INSERT INTO msc_rel_allegati_delibere SET id_delibere = "' . $row['Codice_Delibera'] . '", id_allegati = "' . $row['Allegato_3'] . '" ';
//                echo $query2 . "<br>";
//                $res2 = mysqli_query($db, $query2);
//            }
//
//        }
//    }
}

function setFunzionariDelibere()
{
    global $db;

    $query = "SELECT * 
              FROM TABLE_delibere_full";
    $res = mysqli_query($db, $query);

    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {

            if ($row['Codice_Funzionario'] != "") {
                $query2 = 'INSERT INTO msc_rel_firmatari_delibere SET id_delibere = "' . $row['Codice_Delibera'] . '", id_firmatari = "' . $row['Codice_Funzionario'] . '" ';
                echo $query2 . "<br>";
                $res2 = mysqli_query($db, $query2);
            }

            if ($row['Codice_Funzionario2'] != "") {
                $query2 = 'INSERT INTO msc_rel_firmatari_delibere SET id_delibere = "' . $row['Codice_Delibera'] . '", id_firmatari = "' . $row['Codice_Funzionario2'] . '" ';
                echo $query2 . "<br>";
                $res2 = mysqli_query($db, $query2);
            }

            if ($row['Codice_Funzionario3'] != "") {
                $query2 = 'INSERT INTO msc_rel_firmatari_delibere SET id_delibere = "' . $row['Codice_Delibera'] . '", id_firmatari = "' . $row['Codice_Funzionario3'] . '" ';
                echo $query2 . "<br>";
                $res2 = mysqli_query($db, $query2);
            }

        }
    }
}


function setDelibere()
{
    global $db;

    $query = "SELECT * FROM TABLE_delibere_full";
    $res = mysqli_query($db, $query);

    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {

            $query2 = "INSERT INTO `msc_delibere`(
                    `id`, 
                    `data`,
                    `numero`,
                    `id_stato`,
                    `id_tipologia`, 
                    `argomento`,
                    `finanziamento`,
                    `note`,
                    `note_servizio`,

                    `scheda`,
                    `data_consegna`,
                    `note_firma`,
                    `id_direttore`, 
                    `data_direttore_invio`, 
                    `data_direttore_ritorno`, 
                    `note_direttore`,
                    `invio_mef`,
                    `data_mef_invio`,
                    `data_mef_pec`,
                    `data_mef_ritorno`,
                    `note_mef`,
                    `id_segretario`,
                    `data_segretario_invio`,
                    `data_segretario_ritorno`,
                    `note_segretario`,
                    `id_presidente`,
                    `data_presidente_invio`,
                    `data_presidente_ritorno`,
                    `note_presidente`,
                    `data_invio_cc`,
                    `numero_cc`,
                    `invio_ragioneria_cc`,
                    `data_registrazione_cc`,
                    `id_registro_cc`,
                    `foglio_cc`,
                    `tipo_registrazione_cc`,
                    `note_cc`,

                    `data_invio_cst`,
                    `numero_invio_cst`,
                    `note_invio_cst`,
                    `data_ritorno_cst`,
                    `numero_ritorno_cst`,
                    `note_ritorno_cst`,
                    `data_invio_cu`,
                    `numero_invio_cu`,
                    `note_invio_cu`,
                    `data_ritorno_cu`,
                    `numero_ritorno_cu`,
                    `note_ritorno_cu`,
                    `data_invio_cp`,
                    `numero_invio_cp`,
                    `note_invio_cp`,
                    `data_ritorno_cp`,
                    `numero_ritorno_cp`,
                    `note_ritorno_cp`,
                    `data_invio_p`,
                    `note_p`,

                    `data_invio_gu`,
                    `numero_invio_gu`,
                    `tipo_gu`,
                    `data_gu`,
                    `numero_gu`,
                    `data_ec_gu`,
                    `numero_ec_gu`,
                    `data_co_gu`,
                    `numero_co_gu`,
                    `pubblicazione_gu`,
                    `note_gu`
            ) 
            VALUES (
                    '". $row['Codice_Delibera'] ."',
                    '". $row['Data_Delibera'] ."',
                    '". $row['Numero_Delibera'] ."',
                    '". $row['Codice_StatoDelibera'] ."',
                    '". $row['Codice_Tipologia'] ."',
                    '". addslashes($row['Argomento']) ."',
                    '". $row['Finanziamento'] ."',
                    '". addslashes($row['Nota_Cipe']) ."',
                    '". addslashes($row['Nota_ServizioCipe']) ."',

                    '". $row['Consegna_Scheda'] ."',
                    '". $row['Data_Consegna'] ."',
                    '". addslashes($row['Nota_Consegna']) ."',
                    '". $row['Codice_Direttore'] ."',
                    '". $row['Data_DirettoreInvio'] ."',
                    '". $row['Data_DirettoreRitorno'] ."',
                    '". addslashes($row['Nota_Direttore']) ."',
                    '". $row['Invio_Mef'] ."',
                    '". $row['Data_MefInvio'] ."',
                    '". $row['Data_MefPec'] ."',
                    '". $row['Data_MefRitorno'] ."',
                    '". addslashes($row['Nota_Mef']) ."',
                    '". $row['Codice_Segretario'] ."',
                    '". $row['Data_SegretarioInvio'] ."',
                    '". $row['Data_SegretarioRitorno'] ."',
                    '". addslashes($row['Nota_Segretario']) ."',
                    '". $row['Codice_Presidente'] ."',
                    '". $row['Data_PresidenteInvio'] ."',
                    '". $row['Data_PresidenteRitorno'] ."',
                    '". addslashes($row['Nota_Presidente']) ."',
                    '". $row['Data_InvioCC'] ."',
                    '". $row['Numero_InvioCC'] ."',
                    '". $row['Invio_Ragioneria'] ."',
                    '". $row['Data_RegistrazioneCC'] ."',
                    '". $row['Registro_RegistrazioneCC'] ."',
                    '". $row['Foglio_RegistrazioneCC'] ."',
                    '". $row['Tipo_Registrazione'] ."',
                    '". addslashes($row['Nota_CC']) ."',

                    '". $row['Data_ConferenzaStatoRegioni'] ."',
                    '". $row['Numero_ConferenzaStatoRegioni'] ."',
                    '". addslashes($row['Nota_ConferenzaStatoRegioni']) ."',
                    '". $row['A_Data_ConferenzaStatoRegioni'] ."',
                    '". $row['A_Numero_ConferenzaStatoRegioni'] ."',
                    '". addslashes($row['A_Nota_ConferenzaStatoRegioni']) ."',
                    '". $row['Data_ConferenzaUnificata'] ."',
                    '". $row['Numero_ConferenzaUnificata'] ."',
                    '". addslashes($row['Nota_ConferenzaUnificata']) ."',
                    '". $row['A_Data_ConferenzaUnificata'] ."',
                    '". $row['A_Numero_ConferenzaUnificata'] ."',
                    '". addslashes($row['A_Nota_ConferenzaUnificata']) ."',
                    '". $row['Data_CommissioneParlamentare'] ."',
                    '". $row['Numero_CommissioneParlamentare'] ."',
                    '". addslashes($row['Nota_CommissioneParlamentare']) ."',
                    '". $row['A_Data_CommissioneParlamentare'] ."',
                    '". $row['A_Numero_CommissioneParlamentare'] ."',
                    '". addslashes($row['A_Nota_CommissioneParlamentare']) ."',
                    '". $row['Data_Parlamento'] ."',
                    '". addslashes($row['Nota_Parlamento']) ."',

                    '". $row['Data_InvioGU'] ."',
                    '". $row['Numero_InvioGU'] ."',
                    '". $row['Tipo_GU'] ."',
                    '". $row['Data_GU'] ."',
                    '". $row['Numero_GU'] ."',
                    '". $row['Data_EC'] ."',
                    '". $row['Numero_EC'] ."',
                    '". $row['Data_Co'] ."',
                    '". $row['Numero_Co'] ."',
                    '". $row['Tipo_Pubblicazione'] ."',
                    '". addslashes($row['Nota_GU']) ."'
                    
            )";

            echo $query2 . "<br>";
            $res2 = mysqli_query($db, $query2);
        }
    }
}



function setTableRegistri()
{
    global $db;

    $query = "SELECT * FROM msc_registri as r
							LEFT JOIN TABLE17 as t ON r.id = t.id2
							WHERE r.id >= 7000 AND r.id <=8000";
    $res = mysqli_query($db, $query);

    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {
            //mb_check_encoding($row['COL10'], 'UTF-8')
            //echo $row['mittente2'] . "<br>";

            $query2 = 'UPDATE `msc_registri` SET `mittente`= "' . $row['mittente2'] . '", `annotazioni`= "' . $row['annotazioni2'] . '"  WHERE id = ' . $row['id'];

            echo $query2 . "<br>";
            $res2 = mysqli_query($db, $query2);


        }
    }

}


function setMittentiRegistri()
{
    global $db;

    $query = "SELECT * FROM msc_mittente as m";
    $res = mysqli_query($db, $query);

    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {

            $query2 = 'UPDATE `msc_registri` SET `id_mittente`= "' . $row['id'] . '"  WHERE mittente = "' . $row['denominazione'] . '"';
            echo $query2 . "<br>";
            $res2 = mysqli_query($db, $query2);
        }
    }

}


function setOggettoRegistri()
{
    global $db;

    $query = "SELECT * FROM msc_registri as r
							LEFT JOIN TABLE16 as t ON r.id = t.id3
							WHERE r.id >= 7000 AND r.id <= 8000";
    $res = mysqli_query($db, $query);

    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {
            //mb_check_encoding($row['COL10'], 'UTF-8')
            //echo $row['mittente2'] . "<br>";

            $query2 = 'UPDATE `msc_registri` SET `oggetto`= "' . $row['oggetto3'] . '"  WHERE id = ' . $row['id'];

            echo $query2 . "<br>";
            $res2 = mysqli_query($db, $query2);


        }
    }

}


function updateIdTitolari()
{
    global $db;

    $query = "SELECT * , t.id as idT, r.id as idR FROM msc_fascicoli as r
		LEFT JOIN msc_titolari as t ON r.codice_titolario = t.codice";
    $res = mysqli_query($db, $query);

    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {
            //mb_check_encoding($row['COL10'], 'UTF-8')
            //echo $row['mittente2'] . "<br>";

            $query2 = 'UPDATE `msc_fascicoli` SET `id_titolari`= "' . $row['idT'] . '"  WHERE id = ' . $row['idR'];

            echo $query2 . "<br>";
            $res2 = mysqli_query($db, $query2);


        }
    }

}


function gestioneFilesRegistri()
{
    global $db;
    $path = "../files/REGISTRO_MOSIC";
    //$results = scandir($path);

    //$path = realpath('yourfolder/examplefolder');
    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path)) as $filename) {
        if (!is_dir($filename)) {
            $filename = str_replace("../", "", $filename);
            $exploda_path = explode("/", $filename);

            if (!is_dir("../".$exploda_path[0] . "/" . $exploda_path[1]. "/" . $exploda_path[2]. "/" . $exploda_path[3]. "/" . $exploda_path[4])) { //se NON sono un sottofascicolo
                $exploda_nome = explode("-", $exploda_path[4]);  //$exploda_path[4] = nome file
                //echo $exploda_nome[0] . "<br>";
                echo $filename . " ---> ".$exploda_nome[0]."<br>";
            } else { //altrimenti sono un sottofascicolo
                $exploda_nome = explode("-", $exploda_path[5]);  //$exploda_path[4] = nome file
                echo $filename . " ---> ".$exploda_nome[0]."<br>";
            }

            //inserisco nella tabella allegati Data e $item
            $query2 = "INSERT INTO `msc_allegati`(`data`, `file`) VALUES (NOW(),'". $filename ."')";
            $res2 = mysqli_query($db, $query2);
            //echo $query2."<br>";

            $last_id = mysqli_insert_id($db); //ultimo id inserito

            $query3 = "INSERT INTO `msc_rel_allegati_registri`(`id_registri`, `id_allegati`) VALUES ($exploda_nome[0], $last_id)";
            $res3 = mysqli_query($db, $query3);
        }
    }
}



function setSottofascicoliDenominazioneSuRegistri() {
    global $db;

    $query = "SELECT * FROM msc_sottofascicoli";
    $res = mysqli_query($db, $query);

    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {
            echo $row['Descrizione_SottoFascicolo'] . "<br>";
            $query2 = 'UPDATE `msc_registri` SET `denominazione_sottofascicolo`= "' . $row['Descrizione_SottoFascicolo'] . '"  WHERE numero_sottofascicolo = ' . $row['Numero_SottoFascicolo'];
            $res2 = mysqli_query($db, $query2);
        }
    }
}



function gestioneFilesPreCipe()
{
    global $db;
    $path = "../files/RIUNIONI_PRECIPE";
    //$results = scandir($path);

    //$path = realpath('yourfolder/examplefolder');
    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path)) as $filename) {
        if (!is_dir($filename)) {
            $filename = str_replace("../", "", $filename);
            $exploda_path = explode("/", $filename);

            if ($exploda_path[4] != "" && $exploda_path[4] != "Thumbs.db") {
                echo $exploda_path[4] . " ----------> $exploda_path[3] ---------> $exploda_path[2] <br>"; //nome file  ----> tipo -----> data precipe
                echo $filename . "<br>";

                //inserisco nella tabella allegati Data e $item
                $query2 = "INSERT INTO `msc_allegati`(`data`, `file`) VALUES (NOW(),'" . $filename . "')";
                $res2 = mysqli_query($db, $query2);

                $last_id = mysqli_insert_id($db); //ultimo id inserito


                $query = "SELECT * FROM msc_precipe as p WHERE data = '" . $exploda_path[2] . "'";
                $res = mysqli_query($db, $query);
                $id_precipe = "";
                if (mysqli_num_rows($res) >= 1) {
                    while ($row = mysqli_fetch_array($res)) {
                        $id_precipe = $row['id'];
                        //echo "trovato --> ". $row['id'];

                        if ($exploda_path[3] == "APG" || $exploda_path[3] == "OSS" || $exploda_path[3] == "TLX") {
                            $query3 = "INSERT INTO `msc_rel_allegati_precipe`(`id_precipe`, `id_allegati`, `tipo`) VALUES ($id_precipe, $last_id, '$exploda_path[3]')";
                            $res3 = mysqli_query($db, $query3);
                            echo $query3 . "<br>";
                        }
                    }
                }

            }

            echo "-<br>";


        }
    }
}



function gestioneFilesCipe()
{
    global $db;
    $path = "../files/SEDUTE_CIPE";
    //$results = scandir($path);

    //$path = realpath('yourfolder/examplefolder');
    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path)) as $filename) {
        if (!is_dir($filename)) {
            $filename = str_replace("../", "", $filename);
            $exploda_path = explode("/", $filename);

            if ($exploda_path[4] != "" && $exploda_path[4] != "Thumbs.db") {
                echo $exploda_path[4] . " ----------> $exploda_path[3] ---------> $exploda_path[2] <br>"; //nome file  ----> tipo -----> data precipe
                echo $filename . "<br>";

                //inserisco nella tabella allegati Data e $item
                $query2 = "INSERT INTO `msc_allegati`(`data`, `file`) VALUES (NOW(),'" . $filename . "')";
                $res2 = mysqli_query($db, $query2);

                $last_id = mysqli_insert_id($db); //ultimo id inserito


                $query = "SELECT * FROM msc_cipe as p WHERE data = '" . $exploda_path[2] . "'";
                $res = mysqli_query($db, $query);
                $id_cipe = "";
                if (mysqli_num_rows($res) >= 1) {
                    while ($row = mysqli_fetch_array($res)) {
                        $id_cipe = $row['id'];
                        //echo "trovato --> ". $row['id'];

                        if ($exploda_path[3] == "APG" || $exploda_path[3] == "OSS" || $exploda_path[3] == "TLX" || $exploda_path[3] == "EST") {
                            $query3 = "INSERT INTO `msc_rel_allegati_cipe`(`id_cipe`, `id_allegati`, `tipo`) VALUES ($id_cipe, $last_id, '$exploda_path[3]')";
                            $res3 = mysqli_query($db, $query3);
                            echo $query3 . "<br>";
                        }
                    }
                }

            }

            echo "-<br>";


        }
    }
}


function setIdFascicoli()
{

    global $db;

    $query = "SELECT r.id as idR, f.id as idF, r.numero_fascicolo as numero_fascicoloR, f.numero_fascicolo as numero_fascicoloF, r.codice_titolario as codice_titolarioR, f.codice_titolario as codice_titolarioF 
            FROM msc_registri as r
    		LEFT JOIN msc_fascicoli as f
            ON r.id_fascicoli = f.codice_repertorio";
    //
    //$query = "SELECT r.id as idR, r.numero_fascicolo as numero_fascicoloR, r.codice_titolario as codice_titolarioR, f.id as idF, f.numero_fascicolo as numero_fascicoloF, f.codice_titolario as codice_titolarioF 
    //        FROM msc_registri as r
    //		LEFT JOIN msc_fascicoli as f
    //        ON r.numero_fascicolo = f.numero_fascicolo
    //        AND r.codice_titolario = f.codice_titolario";
    $res = mysqli_query($db, $query);

    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {

            $query2 = 'UPDATE `msc_registri` SET `id_fascicoli`= "' . $row['idF'] . '"  WHERE id = ' . $row['idR'];

            echo $query2 . "<br>";
            $res2 = mysqli_query($db, $query2);
        }
    }
}


function raggruppaPreCipeData()
{
    global $db;

    $query = "SELECT Data_PreCipe
              FROM TABLE_precipe_full 
              GROUP BY Data_PreCipe";
    $res = mysqli_query($db, $query);

    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {
            $query2 = 'INSERT INTO msc_precipe SET data = "' . $row['Data_PreCipe'] . '"';

            echo $query2 . "<br>";
            $res2 = mysqli_query($db, $query2);
        }
    }
}


function setOrdiniPreCipe()
{ //temporaneamente deve esistere la data anche nella tabella ordini!
    global $db;

    $query = "SELECT *
              FROM msc_precipe";
    $res = mysqli_query($db, $query);

    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {
            $query2 = 'SELECT * FROM TABLE_precipe_full WHERE Data_PreCipe = "' . $row['data'] . '"';
            $res2 = mysqli_query($db, $query2);

            if (mysqli_num_rows($res2) >= 1) {
                while ($row2 = mysqli_fetch_array($res2)) {
                    $argomenti = "";
                    $query_argomenti = 'SELECT * FROM msc_argomenti WHERE codice = "' . $row2['Codice_ArgomentoPreCipe'] . '"';
                    $res_argomenti = mysqli_query($db, $query_argomenti);
                    while ($row_argomenti = mysqli_fetch_array($res_argomenti)) {
                        $argomenti = $row_argomenti['id'];
                    }

                    $titolari = "";
                    $query_titolari = 'SELECT * FROM msc_titolari WHERE codice = "' . $row2['Codice_Titolario'] . '"';
                    $res_titolari = mysqli_query($db, $query_titolari);
                    while ($row_titolari = mysqli_fetch_array($res_titolari)) {
                        $titolari = $row_titolari['id'];
                    }

                    $fascicoli = "";
                    $query_fascicoli = 'SELECT * FROM msc_fascicoli WHERE codice_titolario = "' . $row2['Codice_Titolario'] . '" AND numero_fascicolo = "' . $row2['Numero_Fascicolo'] . '"';
                    $res_fascicoli = mysqli_query($db, $query_fascicoli);
                    while ($row_fascicoli = mysqli_fetch_array($res_fascicoli)) {
                        $fascicoli = $row_fascicoli['id'];
                    }

                    $uffici = "";
                    $query_uffici = 'SELECT * FROM msc_uffici WHERE codice = "' . $row2['Codice_Ufficio'] . '"';
                    $res_uffici = mysqli_query($db, $query_uffici);
                    while ($row_uffici = mysqli_fetch_array($res_uffici)) {
                        $uffici = $row_uffici['id'];
                    }

                    $risultanze = "";
                    $query_risultanze = 'SELECT * FROM msc_risultanze WHERE codice = "' . $row2['Codice_RisultanzaPreCipe'] . '"';
                    $res_risultanze = mysqli_query($db, $query_risultanze);
                    while ($row_risultanze = mysqli_fetch_array($res_risultanze)) {
                        $risultanze = $row_risultanze['id'];
                    }


                    $query3 = 'INSERT INTO msc_precipe_ordini SET 
                        id = "' . $row2['Codice_PreCipe'] . '", 
                        id_precipe = "' . $row['id'] . '", 
                        progressivo = "' . $row2['Progressivo_PreCipe'] . '", 
                        id_titolari = "' . $titolari . '", 
                        id_fascicoli = "' . $fascicoli . '", 
                        id_argomenti = "' . $argomenti . '", 
                        id_uffici = "' . $uffici . '", 
                        ordine = "' . $row2['Numero_OdgPreCipe'] . '", 
                        denominazione = "' . $row2['Oggetto_PreCipe'] . '", 
                        risultanza = "' . $risultanze . '", 
                        annotazioni = "' . $row2['Annotazioni_PreCipe'] . '"                        
                      ';

                    echo $query3 . "<br>";
                    $res3 = mysqli_query($db, $query3);
                }
            }
        }
    }
}

function setRegistriPrecipe()
{
    global $db;

    $query = "SELECT * 
              FROM TABLE_precipe_full";
    $res = mysqli_query($db, $query);

    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {

            if ($row['Codice_Registro'] != "") {
                $query2 = 'INSERT INTO msc_rel_registri_odg SET id_odg = "' . $row['Codice_PreCipe'] . '", id_registri = "' . $row['Codice_Registro'] . '" ';
                echo $query2 . "<br>";
                $res2 = mysqli_query($db, $query2);
            }

            if ($row['Codice_Registro2'] != "") {
                $query2 = 'INSERT INTO msc_rel_registri_odg SET id_odg = "' . $row['Codice_PreCipe'] . '", id_registri = "' . $row['Codice_Registro2'] . '" ';
                echo $query2 . "<br>";
                $res2 = mysqli_query($db, $query2);
            }

            if ($row['Codice_Registro3'] != "") {
                $query2 = 'INSERT INTO msc_rel_registri_odg SET id_odg = "' . $row['Codice_PreCipe'] . '", id_registri = "' . $row['Codice_Registro3'] . '" ';
                echo $query2 . "<br>";
                $res2 = mysqli_query($db, $query2);
            }

        }
    }
}


function raggruppaCipeData()
{
    global $db;

    $query = "SELECT *
              FROM TABLE_cipe_full as c
              LEFT JOIN TABLE61 as s ON c.Data_Cipe = s.Data_SedutaCipe
              GROUP BY c.Data_Cipe";
    $res = mysqli_query($db, $query);

    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {
            $query2 = 'INSERT INTO msc_cipe SET 
                        data = "' . $row['Data_Cipe'] . '",
                        ufficiale_riunione = "' . $row['Ufficiale_SedutaCipe'] . '",
                        giorno = "' . $row['Giorno_SedutaCipe'] . '",
                        ora = "' . $row['Ora_SedutaCipe'] . '",
                        sede = "' . $row['Sede_SedutaCipe'] . '",
                        id_presidente = "' . $row['Codice_Presidente'] . '",
                        id_segretario = "' . $row['Codice_Segretario'] . '",
                        id_direttore = "' . $row['Codice_Direttore'] . '"                        
                        ';

            echo $query2 . "<br>";
            $res2 = mysqli_query($db, $query2);
        }
    }
}

function setOrdiniCipe()
{ //temporaneamente deve esistere la data anche nella tabella ordini!
    global $db;

    $query = "SELECT *
              FROM msc_cipe";
    $res = mysqli_query($db, $query);

    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {
            $query2 = 'SELECT * FROM TABLE_cipe_full WHERE Data_Cipe = "' . $row['data'] . '"';
            $res2 = mysqli_query($db, $query2);

            if (mysqli_num_rows($res2) >= 1) {
                while ($row2 = mysqli_fetch_array($res2)) {
                    $argomenti = "";
                    $query_argomenti = 'SELECT * FROM msc_argomenti WHERE codice = "' . $row2['Codice_ArgomentoCipe'] . '"';
                    $res_argomenti = mysqli_query($db, $query_argomenti);
                    while ($row_argomenti = mysqli_fetch_array($res_argomenti)) {
                        $argomenti = $row_argomenti['id'];
                    }

                    $titolari = "";
                    $query_titolari = 'SELECT * FROM msc_titolari WHERE codice = "' . $row2['Codice_Titolario'] . '"';
                    $res_titolari = mysqli_query($db, $query_titolari);
                    while ($row_titolari = mysqli_fetch_array($res_titolari)) {
                        $titolari = $row_titolari['id'];
                    }

                    $fascicoli = "";
                    $query_fascicoli = 'SELECT * FROM msc_fascicoli WHERE codice_titolario = "' . $row2['Codice_Titolario'] . '" AND numero_fascicolo = "' . $row2['Numero_Fascicolo'] . '"';
                    $res_fascicoli = mysqli_query($db, $query_fascicoli);
                    while ($row_fascicoli = mysqli_fetch_array($res_fascicoli)) {
                        $fascicoli = $row_fascicoli['id'];
                    }

                    $uffici = "";
                    $query_uffici = 'SELECT * FROM msc_uffici WHERE codice = "' . $row2['Codice_Ufficio'] . '"';
                    $res_uffici = mysqli_query($db, $query_uffici);
                    while ($row_uffici = mysqli_fetch_array($res_uffici)) {
                        $uffici = $row_uffici['id'];
                    }

                    $risultanze = "";
                    $query_risultanze = 'SELECT * FROM msc_risultanze WHERE codice = "' . $row2['Codice_RisultanzaCipe'] . '"';
                    $res_risultanze = mysqli_query($db, $query_risultanze);
                    while ($row_risultanze = mysqli_fetch_array($res_risultanze)) {
                        $risultanze = $row_risultanze['id'];
                    }


                    $query3 = 'INSERT INTO msc_cipe_ordini SET 
                        id = "' . $row2['Codice_Cipe'] . '", 
                        id_cipe = "' . $row['id'] . '", 
                        progressivo = "' . $row2['Progressivo_Cipe'] . '", 
                        id_titolari = "' . $titolari . '", 
                        id_fascicoli = "' . $fascicoli . '", 
                        id_sottofascicoli = "' . $row2['Numero_SottoFascicolo'] . '", 
                        id_argomenti = "' . $argomenti . '", 
                        tipo_argomenti = "' . $row2['Codice_TipoArgomentoCipe'] . '",  
                        id_uffici = "' . $uffici . '", 
                        ordine = "' . $row2['Numero_OdgCipe'] . '", 
                        denominazione = "' . $row2['Oggetto_Cipe'] . '", 
                        risultanza = "' . $risultanze . '", 
                        id_esito = "' . $row2['Codice_EsitoCipe'] . '", 
                        tipo_esito = "' . $row2['Codice_TipoEsitoCipe'] . '", 
                        annotazioni = "' . $row2['Annotazioni_Cipe'] . '"                        
                      ';

                    echo $query3 . "<br>";
                    $res3 = mysqli_query($db, $query3);
                }
            }
        }
    }
}


function setRegistriCipe()
{
    global $db;

    $query = "SELECT * 
              FROM TABLE_cipe_full";
    $res = mysqli_query($db, $query);

    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {

            if ($row['Codice_Registro'] != "") {
                $query2 = 'INSERT INTO msc_rel_registri_odg_cipe SET id_odg_cipe = "' . $row['Codice_Cipe'] . '", id_registri = "' . $row['Codice_Registro'] . '" ';
                echo $query2 . "<br>";
                $res2 = mysqli_query($db, $query2);
            }

            if ($row['Codice_Registro2'] != "") {
                $query2 = 'INSERT INTO msc_rel_registri_odg_cipe SET id_odg_cipe = "' . $row['Codice_Cipe'] . '", id_registri = "' . $row['Codice_Registro2'] . '" ';
                echo $query2 . "<br>";
                $res2 = mysqli_query($db, $query2);
            }

            if ($row['Codice_Registro3'] != "") {
                $query2 = 'INSERT INTO msc_rel_registri_odg_cipe SET id_odg_cipe = "' . $row['Codice_Cipe'] . '", id_registri = "' . $row['Codice_Registro3'] . '" ';
                echo $query2 . "<br>";
                $res2 = mysqli_query($db, $query2);
            }

        }
    }
}


function checkRegistriFascicoli()
{
    global $db;

    $query = "SELECT * 
              FROM TABLE33_precipe_full";
    $res = mysqli_query($db, $query);

    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {


        }
    }
}


function setUfficiPreCipeOdg()
{
    global $db;

    $query = "SELECT * FROM msc_precipe_ordini";
    $res = mysqli_query($db, $query);

    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {

            $query2 = 'INSERT INTO msc_rel_uffici_precipe SET id_odg_precipe = "' . $row['id'] . '", id_uffici = "' . $row['id_uffici'] . '" ';
            echo $query2 . "<br>";
            $res2 = mysqli_query($db, $query2);

        }
    }
}

function setUfficiCipeOdg()
{
    global $db;

    $query = "SELECT * FROM msc_cipe_ordini";
    $res = mysqli_query($db, $query);

    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {

            $query2 = 'INSERT INTO msc_rel_uffici_cipe SET id_odg_cipe = "' . $row['id'] . '", id_uffici = "' . $row['id_uffici'] . '" ';
            echo $query2 . "<br>";
            $res2 = mysqli_query($db, $query2);

        }
    }
}



function testUpdate($test)
{
    global $db;

    $query2 = 'INSERT INTO ... SET id=20, Data_SedutaCipe = "' . $test.'"';
    $res2 = mysqli_query($db, $query2);
}



function aggiornaStato($id, $stato) {
    global $db;

    $query = 'UPDATE `msc_precipe` SET `public_reserved_status`= "' . $stato . '"  WHERE id = "' . $id . '"';
    $res2 = mysqli_query($db, $query);
}


function aggiornaURL($id, $stato) {
    global $db;

    $query = 'UPDATE `msc_precipe` SET `public_reserved_URL`= "' . $stato . '"  WHERE id = "' . $id . '"';
    $res2 = mysqli_query($db, $query);
}

function aggiornaStatoCipe($id, $stato) {
    global $db;

    $query = 'UPDATE `msc_cipe` SET `public_reserved_status`= "' . $stato . '"  WHERE id = "' . $id . '"';
    $res2 = mysqli_query($db, $query);
}


function aggiornaURLCipe($id, $stato) {
    global $db;

    $query = 'UPDATE `msc_cipe` SET `public_reserved_URL`= "' . $stato . '"  WHERE id = "' . $id . '"';
    $res2 = mysqli_query($db, $query);
}



function sanitizeFilename($f) {
    // a combination of various methods
    // we don't want to convert html entities, or do any url encoding
    // we want to retain the "essence" of the original file name, if possible
    // char replace table found at:
    // http://www.php.net/manual/en/function.strtr.php#98669
    $replace_chars = array(
        '.'=>'', "'"=>'', ' ' => '-', '°' => '',
        'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj','Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
        'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
        'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
        'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
        'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
        'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
        'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f'
    );
    $f = strtr($f, $replace_chars);
    // convert & to "and", @ to "at", and # to "number"
    $f = preg_replace(array('/[\&]/', '/[\@]/', '/[\#]/'), array('-and-', '-at-', '-number-'), $f);
    $f = preg_replace('/[^(\x20-\x7F)]*/','', $f); // removes any special chars we missed
    $f = str_replace(' ', '-', $f); // convert space to hyphen
    $f = str_replace('\'', '', $f); // removes apostrophes
    $f = preg_replace('/[^\w\-\.]+/', '', $f); // remove non-word chars (leaving hyphens and periods)
    $f = preg_replace('/[\-]+/', '-', $f); // converts groups of hyphens into one
    return $f;
}



function renameFile() {
    global $db;
    $path = "../files/REGISTRO_MOSIC";

    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path)) as $filename) {
        if (!is_dir($filename)) {
            $file_info = pathinfo($filename);

            $temp = explode(".",$file_info['basename']);
            $solo_nome = "";
            for ($i=0; $i<count($temp) -1; $i++) {

                $solo_nome = $solo_nome . $temp[$i];
            }

            $nuovo_nome = sanitizeFilename($solo_nome) . "." . $file_info['extension'];

            rename($filename, $file_info['dirname'] . "/" . $nuovo_nome);

            echo $file_info['dirname']. "<br/>";
            echo $nuovo_nome . "<br/>";
            echo $file_info['extension']. "<br/><br/>";


        }
    }
}



function setDateDelibereGiorni() {
    global $db;

    $query = "SELECT * FROM msc_delibere ORDER BY id DESC";
    $res = mysqli_query($db, $query);
    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {
            $GiorniCapoDipartimento = 0;
            $GiorniMef = 0;
            $GiorniSegretario = 0;
            $GiorniPresidente = 0;
            $GiorniCC = 0;
            $GiorniGU = 0;

            $AcquisizioneSegretario = $row['data_consegna'];
            if ($row['data_direttore_ritorno'] != "0000-00-00" && $row['data'] != "0000-00-00" && $row['data_direttore_ritorno'] != "" && $row['data'] != "") {
                $GiorniCapoDipartimento = (strtotime($row['data_direttore_ritorno']) - strtotime($row['data'])) / 86400;
            }
            if ($row['data_mef_ritorno'] != "0000-00-00" && $row['data_mef_invio'] != "0000-00-00" && $row['data_mef_ritorno'] != "" && $row['data_mef_invio'] != "") {
                $GiorniMef = (strtotime($row['data_mef_ritorno']) - strtotime($row['data_mef_invio'])) / 86400;
            }
            if ($row['data_segretario_ritorno'] != "0000-00-00" && $row['data_segretario_invio'] != "0000-00-00" && $row['data_segretario_ritorno'] != "" && $row['data_segretario_invio'] != "") {
                $GiorniSegretario = (strtotime($row['data_segretario_ritorno']) - strtotime($row['data_segretario_invio'])) / 86400;
            }
            if ($row['data_presidente_ritorno'] != "0000-00-00" && $row['data_segretario_invio'] != "0000-00-00" && $row['data_presidente_ritorno'] != "" && $row['data_segretario_invio'] != "") {
                $GiorniPresidente = (strtotime($row['data_presidente_ritorno']) - strtotime($row['data_segretario_invio'])) / 86400;
            }
            if ($row['data_registrazione_cc'] != "0000-00-00" && $row['data_invio_cc'] != "0000-00-00" && $row['data_registrazione_cc'] != "" && $row['data_invio_cc'] != "") {
                $GiorniCC = (strtotime($row['data_registrazione_cc']) - strtotime($row['data_invio_cc'])) / 86400;
            }
            if ($row['data_gu'] != "0000-00-00" && $row['data_invio_gu'] != "0000-00-00" && $row['data_gu'] != "" && $row['data_invio_gu'] != "") {
                $GiorniGU = (strtotime($row['data_gu']) - strtotime($row['data_invio_gu'])) / 86400;
            }



            $query2 = 'INSERT INTO msc_delibere_giorni SET id_delibere = "' . $row['id'] . '", 
            acquisizione_segretario = "' . $AcquisizioneSegretario . '",
            giorni_capo_dipartimento = "' . $GiorniCapoDipartimento . '",
            giorni_mef = "' . $GiorniMef . '",
            giorniSegretario = "' . $GiorniSegretario . '", 
            giorniPresidente = "' . $GiorniPresidente . '", 
            giorni_cc = "' . $GiorniCC . '", 
            giorni_gu = "' . $GiorniGU . '"
            ';
            echo $query2 . "<br>";
            $res2 = mysqli_query($db, $query2);


            if ($row['id'] == 2589) {
                echo $row['id'] . " --> " . $row['data_consegna'] . "</br>";
                echo $GiorniCC . "</br>";
                echo $query2 . "<br>";
            }

        }
    }
}




?>