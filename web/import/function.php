<?php




function setCorteDeiContiDelibere()
{
    global $db;

    $query = "SELECT * 
              FROM DatiDelibera";
    $res = mysqli_query($db, $query);

    //svuoto la tabella
    $query = 'TRUNCATE msc_delibere_cc';
    mysqli_query($db, $query);

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
              FROM DatiDelibera";
    $res = mysqli_query($db, $query);

    
    //svuoto la tabella
    $query = 'TRUNCATE msc_rel_uffici_delibere';
    mysqli_query($db, $query);

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

function setAllegatiDelibere($filePath, $path)
{
    global $db;
    //$path = "../files/DELIBERE/per-anno";

    //svuoto la tabella
    $query = 'TRUNCATE msc_rel_allegati_delibere';
    mysqli_query($db, $query);

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


                    $query = "INSERT INTO `msc_allegati`(`data`, `file`) VALUES (NOW(),'". $filePath . $filename ."')";
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
              FROM DatiDelibera";
    $res = mysqli_query($db, $query);
    
    //svuoto la tabella
    $query = 'TRUNCATE msc_rel_firmatari_delibere';
    mysqli_query($db, $query);


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

    $query = "SELECT * FROM DatiDelibera";
    $res = mysqli_query($db, $query);

    //svuoto la tabella
    $query = 'TRUNCATE msc_delibere';
    mysqli_query($db, $query);

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





function createTitolari() {
    global $db;
    
    $query = "SELECT * FROM Titolario";
    $res = mysqli_query($db, $query);
    
    //svuoto la tabella
    $query = 'TRUNCATE msc_titolari';
    mysqli_query($db, $query);
    
    //popolo la tabella
    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {
            $query = 'INSERT INTO `msc_titolari`(
                                                 `codice`,
                                                 `denominazione`,
                                                 `descrizione`
                                                )
                                                VALUES (
                                                "' . $row['Codice_Titolario'] . '",
                                                "' . $row['Nome_Titolario'] . '",
                                                "' . $row['Descrizione_Titolario'] . '"
                                                )';
            //echo $query . "<br>";
            $res2 = mysqli_query($db, $query);
        }
    }
}

function createMittenti() {
    global $db;

    $query = "SELECT * FROM Registro GROUP BY Mittente_Registro";
    $res = mysqli_query($db, $query);
    
    //svuoto la tabella
    $query = 'TRUNCATE msc_mittenti';
    mysqli_query($db, $query);
    
    //popolo la tabella
    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {
            if ($row['Mittente_Registro'] != "" && $row['Mittente_Registro'] != " ") {
                $query = 'INSERT INTO `msc_mittenti`(
                                                     `denominazione`
                                                    )
                                                    VALUES (
                                                    "' . $row['Mittente_Registro'] . '"
                                                    )';
                //echo $query . "<br>";
                mysqli_query($db, $query);
            }
        }
    }
}

function createAmministrazioni() {
    global $db;

    $query = "SELECT * FROM Amministrazioni ORDER BY Codice_Amministrazione";
    $res = mysqli_query($db, $query);
    
    //svuoto la tabella
    $query = 'TRUNCATE msc_amministrazioni';
    mysqli_query($db, $query);
    
    //popolo la tabella
    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {
            $query = 'INSERT INTO `msc_amministrazioni`(
                                                 `codice`,
                                                 `denominazione`
                                                )
                                                VALUES (
                                                "' . $row['Codice_Amministrazione'] . '",
                                                "' . $row['Descrizione_Amministrazione'] . '"
                                                )';
            //echo $query . "<br>";
            mysqli_query($db, $query);
        }
    }
}


function createArgomenti() {
    global $db;
    
    $query = "SELECT * FROM ArgomentiCipe";
    $res = mysqli_query($db, $query);
    
    //svuoto la tabella
    $query = 'TRUNCATE msc_argomenti';
    mysqli_query($db, $query);
    
    //popolo la tabella
    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {
            $query = 'INSERT INTO `msc_argomenti`(
                                                 `codice`,
                                                 `denominazione`
                                                )
                                                VALUES (
                                                "' . $row['Codice_ArgomentoCipe'] . '",
                                                "' . $row['Descrizione_ArgomentoCipe'] . '"
                                                )';
            //echo $query . "<br>";
            $res2 = mysqli_query($db, $query);
        }
    }
}

function createUffici() {
    global $db;
    
    $query = "SELECT * FROM UfficiDipe";
    $res = mysqli_query($db, $query);
    
    //svuoto la tabella
    $query = 'TRUNCATE msc_uffici';
    mysqli_query($db, $query);
    
    //popolo la tabella
    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {
            $query = 'INSERT INTO `msc_uffici`(
                                                 `codice`,
                                                 `codice_direzione`,
                                                 `denominazione`,
                                                 `ordine_ufficio`,
                                                 `disattivo_ufficio`,
                                                 `solo_delibere`
                                                )
                                                VALUES (
                                                "' . $row['Codice_Ufficio'] . '",
                                                "' . $row['Codice_Direzione'] . '",
                                                "' . $row['Descrizione_Ufficio'] . '",
                                                "' . $row['Ordine_Ufficio'] . '",
                                                "' . $row['Disattivo_Ufficio'] . '",
                                                "' . $row['Solo_Delibere'] . '"
                                                )';
            //echo $query . "<br>";
            $res2 = mysqli_query($db, $query);
        }
    }
}


function createRisultanze() {
    global $db;
    
    $query = "SELECT * FROM Risultanze_PreCipe";
    $res = mysqli_query($db, $query);
    
    //svuoto la tabella
    $query = 'TRUNCATE msc_risultanze';
    mysqli_query($db, $query);
    
    //popolo la tabella
    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {
            $query = 'INSERT INTO `msc_risultanze`(
                                                 `codice`,
                                                 `denominazione`
                                                )
                                                VALUES (
                                                "' . $row['Codice_RisultanzaPreCipe'] . '",
                                                "' . $row['Descrizione_RisultanzaPreCipe'] . '"
                                                )';
            //echo $query . "<br>";
            $res2 = mysqli_query($db, $query);
        }
    }
}

function createTipoArgomentiCipe() {
    global $db;
    
    $query = "SELECT * FROM TipoArgomentiCipe";
    $res = mysqli_query($db, $query);

    //svuoto la tabella
    $query2 = 'TRUNCATE msc_argomenti_tipo_cipe';
    mysqli_query($db, $query2);

    echo mysqli_num_rows($res);

    //popolo la tabella
    if (mysqli_num_rows($res) >= 1) {


        while ($row = mysqli_fetch_array($res)) {



            $query = 'INSERT INTO `msc_argomenti_tipo_cipe`(
                                                 `id`,
                                                 `denominazione`
                                                )
                                                VALUES (
                                                "' . $row['Codice_TipoArgomentoCipe'] . '",
                                                "' . $row['Descrizione_TipoArgomentoCipe'] . '"
                                                )';
            echo $query . "<br>";
            $res2 = mysqli_query($db, $query);
        }
    }
}

function createTipoEsitiCipe() {
    global $db;
    
    $query = "SELECT * FROM TipoEsitiCipe";
    $res = mysqli_query($db, $query);
    
    //svuoto la tabella
    $query = 'TRUNCATE msc_esiti_tipo_cipe';
    mysqli_query($db, $query);
    
    //popolo la tabella
    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {
            $query = 'INSERT INTO `msc_esiti_tipo_cipe`(
                                                 `id`,
                                                 `denominazione`
                                                )
                                                VALUES (
                                                "' . $row['Codice_TipoEsitoCipe'] . '",
                                                "' . $row['Descrizione_TipoEsitoCipe'] . '"
                                                )';
            echo $query . "<br>";
            $res2 = mysqli_query($db, $query);
        }
    }
}

function createFirmatari() {
    global $db;
    
    $query = "SELECT * FROM Firmatari";
    $res = mysqli_query($db, $query);
    
    //svuoto la tabella
    $query = 'TRUNCATE msc_firmatari';
    mysqli_query($db, $query);
    
    //popolo la tabella
    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {
                $query = 'INSERT INTO `msc_firmatari`(
                                                     `id`,
                                                     `chiave`,
                                                     `tipo`,
                                                     `denominazione`,
                                                     `denominazione_estesa`,
                                                     `disattivato`
                                                    )
                                                    VALUES (
                                                    "' . $row['Codice_Firmatario'] . '",
                                                    "' . $row['Chiave'] . '",
                                                    "' . $row['Tipo_Firmatario'] . '",
                                                    "' . $row['Descrizione_firmatario'] . '",
                                                    "' . $row['Descrizione_Estesa'] . '",
                                                    "' . $row['Disattivato_Firmatario'] . '"
                                                    )';
                echo $query . "<br>";
                $res2 = mysqli_query($db, $query);
        }
    }
}

function createRuoliCipe() {
    global $db;
    
    $query = "SELECT * FROM Livelli";
    $res = mysqli_query($db, $query);
    
    //svuoto la tabella
    $query = 'TRUNCATE msc_ruoli_cipe';
    mysqli_query($db, $query);
    
    //popolo la tabella
    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {
                $query = 'INSERT INTO `msc_ruoli_cipe`(
                                                     `codice`,
                                                     `denominazione`
                                                    )
                                                    VALUES (
                                                    "' . $row['Codice_Livello'] . '",
                                                    "' . $row['Descrizione_Livello'] . '"
                                                    )';
                echo $query . "<br>";
                $res2 = mysqli_query($db, $query);
        }
    }
}

function createLastUpdates() {
    global $db;
    
    
    //svuoto la tabella
    $query = 'TRUNCATE msc_last_updates';
    mysqli_query($db, $query);
    
    $query1 = "CREATE TABLE IF NOT EXISTS `msc_last_updates` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `tabella` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                `lastUpdate` datetime NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=25 ;
                
                INSERT INTO `msc_last_updates` (`id`, `tabella`, `lastUpdate`) VALUES
                (1, 'titolari', '2017-06-01 15:15:57'),
                (2, 'fascicoli', '2017-06-01 15:14:36'),
                (3, 'registri', '2017-06-02 22:29:02'),
                (4, 'amministrazioni', '2017-06-02 22:28:30'),
                (5, 'mittenti', '2017-06-01 14:58:17'),
                (6, 'tags', '2017-06-01 15:20:38'),
                (7, 'uffici', '2017-06-01 15:18:45'),
                (8, 'ruoli_cipe', '2017-03-08 17:34:49'),
                (9, 'groups', '2017-06-01 15:22:24'),
                (10, 'argomenti', '2017-03-08 11:07:05'),
                (11, 'precipe', '2017-06-01 14:24:01'),
                (12, 'precipeodg', '2017-05-29 21:06:24'),
                (13, 'firmatari', '2017-06-01 14:51:43'),
                (14, 'cipe', '2017-06-01 14:55:09'),
                (15, 'cipeodg', '2017-05-29 19:29:58'),
                (16, 'firmataritipo', '2017-04-14 02:00:00'),
                (17, 'cipeesiti', '2017-04-14 02:00:00'),
                (18, 'cipeesititipo', '2017-04-14 01:00:00'),
                (19, 'cipeargomentitipo', '2017-04-14 01:00:00'),
                (20, 'users', '2017-06-01 15:27:20'),
                (21, 'delibere', '2017-06-02 19:54:26'),
                (22, 'adempimenti', '2017-06-01 13:49:18'),
                (23, 'monitor', '2017-06-02 19:54:26'),
                (24, 'monitor_group', '2017-06-02 19:54:26');";
    mysqli_query($db, $query1);


}







function createTipoFirmatari() {
    global $db;
    
    $query = "SELECT * FROM TipoFirmatari";
    $res = mysqli_query($db, $query);
    
    //svuoto la tabella
    $query = 'TRUNCATE msc_firmatari_tipo';
    mysqli_query($db, $query);
    
    //popolo la tabella
    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {
            if ($row['Codice_TipoFirmatario'] != 0) {
                $query = 'INSERT INTO `msc_firmatari_tipo`(
                                                     `id`,
                                                     `denominazione`
                                                    )
                                                    VALUES (
                                                    "' . $row['Codice_TipoFirmatario'] . '",
                                                    "' . $row['Descrizione_TipoFirmatario'] . '"
                                                    )';
                echo $query . "<br>";
                $res2 = mysqli_query($db, $query);
            }
        }
    }
}

function createAdempimenti() {
    global $db;
    
    $query = "SELECT * FROM Adempimenti";
    $res = mysqli_query($db, $query);
    
    //svuoto la tabella
    $query = 'TRUNCATE msc_adempimenti';
    mysqli_query($db, $query);
    
    //popolo la tabella
    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {
            
            //ricavo l'id dell'utente
            $query2 = 'SELECT * FROM fos_user WHERE username = "'. $row['Utente_Modifica'] . '"';
            $res2 = mysqli_query($db, $query2);
        
            $idUtente = 0;
            if (mysqli_num_rows($res2) >= 1) {
                while ($row2 = mysqli_fetch_array($res2)) {
                    $idUtente = $row2['id'];    
                }
            }

            
            $query = 'INSERT INTO `msc_adempimenti`(
                                                 `codice`,
                                                 `progressivo`,
                                                 `codice_scheda`,
                                                 `id_delibere`,
                                                 `descrizione`,
                                                 `codice_descrizione`,
                                                 `codice_fonte`,
                                                 `codice_esito`,
                                                 `data_scadenza`,
                                                 `giorni_scadenza`,
                                                 `mesi_scadenza`,
                                                 `anni_scadenza`,
                                                 `vincolo`,
                                                 `note`,
                                                 `utente`,
                                                 `data_modifica`
                                                )
                                                VALUES (
                                                "' . $row['Codice_Adempimento'] . '",
                                                "' . $row['Progressivo_Adempimento'] . '",
                                                "' . $row['Codice_Scheda'] . '",
                                                "' . $row['Codice_Delibera'] . '",
                                                "' . $row['Descrizione_Adempimento'] . '",
                                                "' . $row['Codice_DescrizioneAdempimento'] . '",
                                                "' . $row['Codice_FonteAdempimento'] . '",
                                                "' . $row['Codice_EsitoAdempimento'] . '",
                                                "' . $row['Data_Scadenza_Adempimento'] . '",
                                                "' . $row['Giorni_Scadenza_Adempimento'] . '",
                                                "' . $row['Mesi_Scadenza_Adempimento'] . '",
                                                "' . $row['Anni_Scadenza_Adempimento'] . '",
                                                "' . $row['Vincolo_Adempimento'] . '",
                                                "' . $row['Note_Adempimento'] . '",
                                                "' . $idUtente . '",
                                                "' . $row['Data_UtenteModifica'] . '"
                                                )';
            echo $query . "<br>";
            mysqli_query($db, $query);
        }
    }
}


function createUtenti() {
    global $db;
    
    $query = "SELECT * FROM PwUtenti";
    $res = mysqli_query($db, $query);
    
    //svuoto la tabella
    $query = 'TRUNCATE fos_user';
    mysqli_query($db, $query);
    
        //svuoto la tabella
    $query = 'TRUNCATE fos_user_group';
    mysqli_query($db, $query);

    //popolo la tabella
    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {
            $enabled = 1;
            if ($row['CessatoServizio'] == 1) {
                $enabled = 0;
            }
            
            $salt = "";
            $password = "";
            $roles = "a:0:{}";
            
            
            
            
            $idRuolo = 0;
            $query4 = 'SELECT * FROM msc_ruoli_cipe WHERE codice = "'.$row['Codice_Livello'].'"';
            $res4 = mysqli_query($db, $query4);
            echo $query4 . "<br>";
            if (mysqli_num_rows($res4) >= 1) {
                while ($row4 = mysqli_fetch_array($res4)) {
                    $idRuolo = $row4['id'];
                }
            }
            


            $salt = uniqid(mt_rand());
            $password = password_hash($row['Password'], PASSWORD_DEFAULT);
            
            $query = 'INSERT INTO `fos_user`(
                                                 `id`,
                                                 `username`,
                                                 `email`,
                                                 `enabled`,
                                                 `salt`,
                                                 `password`,
                                                 `roles`,
                                                 `username_canonical`,
                                                 `email_canonical`,
                                                 `firstName`,
                                                 `lastName`,
                                                 `created`,
                                                 `id_uffici`,
                                                 `cessato_servizio`,
                                                 `ip`,
                                                 `stazione`,
                                                 `id_ruoli_cipe`
                                                )
                                                VALUES (
                                                "' . $row['chiave'] . '",
                                                "' . $row['Userid'] . '",
                                                "' . $row['PostaElettronica'] . '",
                                                "' . $enabled . '",
                                                "' . $salt . '",
                                                "' . $password . '",
                                                "' . $roles . '",
                                                "' . $row['Userid'] . '",
                                                "' . $row['PostaElettronica'] . '",
                                                "' . $row['Nome'] . '",
                                                "' . $row['Cognome'] . '",
                                                NOW(),
                                                "' . $row['Codice_Ufficio'] . '",
                                                "' . $row['CessatoServizio'] . '",
                                                "' . $row['Ip'] . '",
                                                "' . $row['Stazione'] . '",
                                                "' . $idRuolo . '"
                                                )';
            //echo $query . "<br>";
            $res2 = mysqli_query($db, $query);
            
            
            
            $idGruppo = 0;
            $query1 = 'SELECT * FROM fos_group WHERE codice = "'.$row['Codice_Livello'].'"';
            $res1 = mysqli_query($db, $query1);
            //echo $query1 . "<br>";
            if (mysqli_num_rows($res1) >= 1) {
                while ($row1 = mysqli_fetch_array($res1)) {
                    $idGruppo = $row1['id'];
                }
            }
            

          
            $query3 = 'INSERT INTO `fos_user_group`(
                                                 `user_id`,
                                                 `group_id`
                                                )
                                                VALUES (
                                                "' . $row['chiave'] . '",
                                                "' . $idGruppo . '"
                                                )';
            //echo $query3 . "<br>";
            mysqli_query($db, $query3);

        }
    }
}



function setRelAmministrazioniFascicoli() {
    global $db;

    $query = "SELECT id, id_amministrazione FROM msc_fascicoli";
    $res = mysqli_query($db, $query);
    
    //svuoto la tabella
    $query = 'TRUNCATE msc_rel_amministrazioni_fascicoli';
    mysqli_query($db, $query);
    
    //popolo la tabella
    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {
            $query = 'INSERT INTO `msc_rel_amministrazioni_fascicoli`(
                                                 `id_fascicoli`,
                                                 `id_amministrazioni`
                                                )
                                                VALUES (
                                                "' . $row['id'] . '",
                                                "' . $row['id_amministrazione'] . '"
                                                )';
            //echo $query . "<br>";
            mysqli_query($db, $query);
        }
    }
}

function setRelAmministrazioniRegistri() {
    global $db;
    $query = "SELECT id, id_amministrazione FROM msc_registri";
    $res = mysqli_query($db, $query);
    
    //svuoto la tabella
    $query = 'TRUNCATE msc_rel_amministrazioni_registri';
    mysqli_query($db, $query);
    
    //popolo la tabella
    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {
            echo "aa";

            $query = 'INSERT INTO `msc_rel_amministrazioni_registri`(
                                                 `id_registri`,
                                                 `id_amministrazioni`
                                                )
                                                VALUES (
                                                "' . $row['id'] . '",
                                                "' . $row['id_amministrazione'] . '"
                                                )';
            echo $query . "<br>";
            mysqli_query($db, $query);
        }
    }
}


function createRegistri() {
    global $db;
    
    $query = "SELECT * FROM Registro LIMIT 100";
    $res = mysqli_query($db, $query);
    
    //svuoto la tabella
    $query = 'TRUNCATE msc_registri';
    mysqli_query($db, $query);
    $count = 0;
    //popolo la tabella
    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {
            
            //echo $count . " <br/> " . $row['Codice_Registro']. " <br/> "; $count++;
            
            //GET Sottofascicolo
            $query1 = "SELECT Descrizione_SottoFascicolo FROM SottoFascicoli WHERE Numero_SottoFascicolo = '" .$row['Numero_SottoFascicolo']. "'";
            //echo $query1 . "<br>";
            $res1 = mysqli_query($db, $query1);
            $descrizioneSottofascicolo = "";
            while ($row1 = mysqli_fetch_array($res1)) {
                $descrizioneSottofascicolo = $row1['Descrizione_SottoFascicolo'];
            }
            
            //GET id_titolari
            $query2 = "SELECT id FROM msc_titolari WHERE codice = '" .$row['Codice_Titolario']. "'";
            //echo $query2 . "<br>";
            $res2 = mysqli_query($db, $query2);
            $idTitolario = "";
            while ($row2 = mysqli_fetch_array($res2)) {
                $idTitolario = $row2['id'];
            }

            //GET id_mittenti
            $query3 = "SELECT id FROM msc_mittenti WHERE denominazione = '" .$row['Mittente_Registro']. "'";
            //echo $query3 . "<br>";
            $res3 = mysqli_query($db, $query3);
            $denominazioneMittente = "";
            while ($row3 = mysqli_fetch_array($res3)) {
                $denominazioneMittente = $row3['id'];
            }
            
            
            
            $query = 'INSERT INTO `msc_registri`(`id`,
                                                  `data_arrivo`,
                                                  `protocollo_arrivo`,
                                                  `data_mittente`,
                                                  `protocollo_mittente`,
                                                  `oggetto`,
                                                  `id_amministrazione`,
                                                  `mittente`,
                                                  `codice_titolario`,
                                                  `numero_fascicolo`,
                                                  `numero_sottofascicolo`,
                                                  `proposta_cipe`,
                                                  `annotazioni`,
                                                  
                                                  `id_fascicoli`,
                                                  `id_mittenti`,
                                                  `id_titolari`,
                                                  `denominazione_sottofascicolo`
                                                )
                                                 VALUES (
                                                  "' . $row['Codice_Registro'] . '",
                                                  "' . $row['Data_Arrivo'] . '",
                                                  "' . $row['Protocollo_Arrivo'] . '",
                                                  "' . $row['Data_Mittente'] . '",
                                                  "' . $row['Protocollo_Mittente'] . '",
                                                  "' . str_replace('"','',$row['Oggetto_Registro']) . '",
                                                  "' . $row['Codice_Amministrazione'] . '",
                                                  "' . $row['Mittente_Registro'] . '",
                                                  "' . $row['Codice_Titolario'] . '",
                                                  "' . $row['Numero_Fascicolo'] . '",
                                                  "' . $row['Numero_SottoFascicolo'] . '",
                                                  "' . $row['Proposta_Cipe'] . '",
                                                  "' . $row['Annotazioni_Registro'] . '",

                                                  "' . 0 . '",
                                                  "' . $denominazioneMittente . '",
                                                  "' . $idTitolario . '",
                                                  "' . $descrizioneSottofascicolo . '"
                                                )';

            //echo $query . "<br><br>";
            mysqli_query($db, $query);
        }
    }

}


function createEsitiCipe() {
    global $db;

    $query = "SELECT * FROM EsitiCipe";
    $res = mysqli_query($db, $query);
    
    //svuoto la tabella
    $query = 'TRUNCATE msc_esiti_cipe';
    mysqli_query($db, $query);
    
    //popolo la tabella
    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {
            if ($row['Codice_EsitoCipe'] != 0) {
                $query = 'INSERT INTO `msc_esiti_cipe`(
                                                     `id`,
                                                     `denominazione`
                                                    )
                                                    VALUES (
                                                    "' . $row['Codice_EsitoCipe'] . '",
                                                    "' . $row['Descrizione_EsitoCipe'] . '"
                                                    )';
                //echo $query . "<br>";
                mysqli_query($db, $query);
            }
        }
    }
}

function createFascicoli() {
    global $db;
    
    $query = "SELECT * FROM Repertorio";
    $res = mysqli_query($db, $query);
    
    //svuoto la tabella
    $query = 'TRUNCATE msc_fascicoli';
    mysqli_query($db, $query);
    $count = 0;
    //popolo la tabella
    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {
               
            //GET id_esiti_cipe
            $query1 = "SELECT id FROM msc_esiti_cipe WHERE denominazione = '" .$row['Esito_Cipe']. "'";
            //echo $query1 . "<br>";
            $res1 = mysqli_query($db, $query1);
            $idEsitoCipe = "";
            while ($row1 = mysqli_fetch_array($res1)) {
                $idEsitoCipe = $row1['id'];
            }                   
            //GET id_titolari
            $query2 = "SELECT id FROM msc_titolari WHERE codice = '" .$row['Codice_Titolario']. "'";
            //echo $query2 . "<br>";
            $res2 = mysqli_query($db, $query2);
            $idTitolario = "";
            while ($row2 = mysqli_fetch_array($res2)) {
                $idTitolario = $row2['id'];
            }
          
            
            
            $query = 'INSERT INTO `msc_fascicoli`(
                                                  `codice_repertorio`,
                                                  `codice_titolario`,
                                                  `numero_fascicolo`,
                                                  `argomento`,
                                                  `id_amministrazione`,
                                                  `data_magazzino`,
                                                  `data_cipe`,
                                                  `data_cipe2`,
                                                  `archiviazione_repertorio`,
                                                  `annotazioni`,
                                                  
                                                  `id_numeri_delibera`,
                                                  `id_esiti_cipe`,
                                                  `id_archivio_repertorio`,
                                                  `id_titolari`
                                                )
                                                 VALUES (
                                                  "' . $row['Codice_Repertorio'] . '",
                                                  "' . $row['Codice_Titolario'] . '",
                                                  "' . $row['Numero_Fascicolo'] . '",
                                                  "' . str_replace('"','',$row['Argomento_Repertorio']) . '",
                                                  "' . $row['Codice_Amministrazione'] . '",
                                                  "' . $row['Data_Magazzino'] . '",
                                                  "' . $row['Data_Cipe'] . '",
                                                  "' . $row['Data_Cipe2'] . '",
                                                  "' . $row['Archiviazione_Repertorio'] . '",
                                                  "' . str_replace('"','',$row['Annotazioni_Repertorio']) . '",

                                                  "' . $row['Numero_Delibera'] . '",
                                                  "' . $idEsitoCipe . '",
                                                  "' . 0 . '",
                                                  "' . $idTitolario . '"
                                                )';

            //echo $query . "<br><br>";
            mysqli_query($db, $query);
        }
    }
}

function setIdFascicoliRegistri() {
    global $db;

    
    
    $query = "SELECT r.*, r.id as idR, f.id as fascicolo_id FROM `msc_registri` as r
              LEFT JOIN msc_fascicoli as f ON r.numero_fascicolo = f.numero_fascicolo AND r.codice_titolario = f.codice_titolario";
    $res = mysqli_query($db, $query);
    echo $query . "<br>";

    //aggiorno tabella
    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {
            $query = 'UPDATE `msc_registri` SET `id_fascicoli`= "' . $row['fascicolo_id'] . '"  WHERE id = "' . $row['idR'] . '"';
            echo $query . "<br>";
            mysqli_query($db, $query);
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


function gestioneFilesRegistri($filePath, $path)
{
    global $db;
    //$path = "../files/REGISTRO_MOSIC";
    //$results = scandir($path);
    
    //svuoto la tabella
    $query = 'TRUNCATE msc_rel_allegati_registri';
    mysqli_query($db, $query);

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
            $query2 = "INSERT INTO `msc_allegati`(`data`, `file`) VALUES (NOW(),'". $filePath . $filename ."')";
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



function gestioneFilesPreCipe($filePath, $path)
{
    global $db;
    //$path = "../files/RIUNIONI_PRECIPE";
    //$results = scandir($path);

    
    //svuoto la tabella
    $query = 'TRUNCATE msc_rel_allegati_precipe';
    mysqli_query($db, $query);

    //$path = realpath('yourfolder/examplefolder');
    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path)) as $filename) {
        if (!is_dir($filename)) {
            $filename = str_replace("../", "", $filename);
            $exploda_path = explode("/", $filename);

            if ($exploda_path[4] != "" && $exploda_path[4] != "Thumbs.db") {
                echo $exploda_path[4] . " ----------> $exploda_path[3] ---------> $exploda_path[2] <br>"; //nome file  ----> tipo -----> data precipe
                echo $filename . "<br>";

                //inserisco nella tabella allegati Data e $item
                $query2 = "INSERT INTO `msc_allegati`(`data`, `file`) VALUES (NOW(),'" . $filePath . $filename . "')";
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



function gestioneFilesCipe($filePath, $path)
{
    global $db;
    //$path = "../files/SEDUTE_CIPE";
    //$results = scandir($path);

    //svuoto la tabella
    $query = 'TRUNCATE msc_rel_allegati_cipe';
    mysqli_query($db, $query);

    //$path = realpath('yourfolder/examplefolder');
    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path)) as $filename) {
        if (!is_dir($filename)) {
            $filename = str_replace("../", "", $filename);
            $exploda_path = explode("/", $filename);

            if ($exploda_path[4] != "" && $exploda_path[4] != "Thumbs.db") {
                echo $exploda_path[4] . " ----------> $exploda_path[3] ---------> $exploda_path[2] <br>"; //nome file  ----> tipo -----> data precipe
                echo $filename . "<br>";

                //inserisco nella tabella allegati Data e $item
                $query2 = "INSERT INTO `msc_allegati`(`data`, `file`) VALUES (NOW(),'" . $filePath . $filename . "')";
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


function raggruppaPreCipe()
{
    global $db;
    
    //svuoto la tabella
    $query = 'TRUNCATE msc_precipe';
    mysqli_query($db, $query);


    $query = "SELECT Data_PreCipe FROM PreCipe GROUP BY Data_PreCipe";
    $res = mysqli_query($db, $query);

    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {
            $query2 = 'INSERT INTO msc_precipe SET data = "' . $row['Data_PreCipe'] . '"';

            //echo $query2 . "<br>";
            $res2 = mysqli_query($db, $query2);
        }
    }
}


function setOrdiniPreCipe()
{ //temporaneamente deve esistere la data anche nella tabella ordini!
    global $db;

    $query = "SELECT * FROM msc_precipe";
    $res = mysqli_query($db, $query);

    //svuoto la tabella
    $query = 'TRUNCATE msc_precipe_ordini';
    mysqli_query($db, $query);

    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {
            $query2 = 'SELECT * FROM PreCipe WHERE Data_PreCipe = "' . $row['data'] . '"';
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

                    //echo $query3 . "<br>";
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
              FROM PreCipe";
    $res = mysqli_query($db, $query);

    //svuoto la tabella
    $query = 'TRUNCATE msc_rel_registri_odg';
    mysqli_query($db, $query);

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


function raggruppaCipe() {
    global $db;

    $query = "SELECT * FROM Cipe as c
              LEFT JOIN Sedute_Cipe as s ON c.Data_Cipe = s.Data_SedutaCipe
              GROUP BY c.Data_Cipe";
    $res = mysqli_query($db, $query);

    //svuoto la tabella
    $query = 'TRUNCATE msc_cipe';
    mysqli_query($db, $query);

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

            //echo $query2 . "<br>";
            $res2 = mysqli_query($db, $query2);
        }
    }
}



function setOrdiniCipe()
{ //temporaneamente deve esistere la data anche nella tabella ordini!
    global $db;

    $query = "SELECT * FROM msc_cipe";
    $res = mysqli_query($db, $query);

    //svuoto la tabella
    $query = 'TRUNCATE msc_cipe_ordini';
    mysqli_query($db, $query);

    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {
            $query2 = 'SELECT * FROM Cipe WHERE Data_Cipe = "' . $row['data'] . '"';
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

                    //echo $query3 . "<br>";
                    $res3 = mysqli_query($db, $query3);
                }
            }
        }
    }
}


function setRegistriCipe() {
    global $db;

    $query = "SELECT * FROM Cipe";
    $res = mysqli_query($db, $query);

    //svuoto la tabella
    $query = 'TRUNCATE msc_rel_registri_odg_cipe';
    mysqli_query($db, $query);

    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {

            if ($row['Codice_Registro'] != "") {
                $query2 = 'INSERT INTO msc_rel_registri_odg_cipe SET id_odg_cipe = "' . $row['Codice_Cipe'] . '", id_registri = "' . $row['Codice_Registro'] . '" ';
                //echo $query2 . "<br>";
                $res2 = mysqli_query($db, $query2);
            }

            if ($row['Codice_Registro2'] != "") {
                $query2 = 'INSERT INTO msc_rel_registri_odg_cipe SET id_odg_cipe = "' . $row['Codice_Cipe'] . '", id_registri = "' . $row['Codice_Registro2'] . '" ';
                //echo $query2 . "<br>";
                $res2 = mysqli_query($db, $query2);
            }

            if ($row['Codice_Registro3'] != "") {
                $query2 = 'INSERT INTO msc_rel_registri_odg_cipe SET id_odg_cipe = "' . $row['Codice_Cipe'] . '", id_registri = "' . $row['Codice_Registro3'] . '" ';
                //echo $query2 . "<br>";
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

    //svuoto la tabella
    $query = 'TRUNCATE msc_rel_uffici_precipe';
    mysqli_query($db, $query);

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

    //svuoto la tabella
    $query = 'TRUNCATE msc_rel_uffici_cipe';
    mysqli_query($db, $query);

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



function renameFile($path) {
    global $db;
    //$path = "../files/REGISTRO_MOSIC";

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

            //svuoto la tabella
            $query = 'TRUNCATE msc_allegati';
            mysqli_query($db, $query);


        }
    }
}



function setDateDelibereGiorni() {
    global $db;

    $query = "SELECT * FROM msc_delibere ORDER BY id DESC";
    $res = mysqli_query($db, $query);
    
    //svuoto la tabella
    $query = 'TRUNCATE msc_delibere_giorni';
    mysqli_query($db, $query);

    
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

function setUtentiAdempimenti() {
    global $db;

    $query = "SELECT * 
              FROM Adempimenti as a
              LEFT JOIN fos_user as u ON a.Utente_Modifica = u.username";
    $res = mysqli_query($db, $query);

    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {
            $query2 = 'UPDATE `msc_adempimenti` SET `utente`= "' . $row['id'] . '" WHERE `progressivo` = "' . $row['Progressivo_Adempimento'] . '" AND `codice_scheda` = "' . $row['Codice_Scheda'] .'" AND `id_delibere` = "' . $row['Codice_Delibera'] .'"';

            echo $query2 . "<br>";
            $res2 = mysqli_query($db, $query2);

        }
    }
}



function differenzaDate($data_iniziale,$data_finale,$unita) {
    if ($data_iniziale == null || $data_iniziale == "" || $data_iniziale == "0000-00-00" || $data_finale == null || $data_finale == "" || $data_finale == "0000-00-00") {
        return null;
    }
    $data1 = strtotime($data_iniziale);
    $data2 = strtotime($data_finale);

    switch($unita) {
        case "m": $unita = 1/60; break; 	//MINUTI
        case "h": $unita = 1; break;		//ORE
        case "g": $unita = 24; break;		//GIORNI
        case "a": $unita = 8760; break;         //ANNI
    }

    $differenza = (($data2-$data1)/3600)/$unita;
    return $differenza;
}
function monitor() {
    global $db;

    $query = "SELECT * FROM msc_delibere ORDER BY data DESC";
    $res = mysqli_query($db, $query);

    $arrayDelibere = array();

    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {

            if (!isset($arrayDelibere[$row['data']][$row['id']])){
                $arrayDelibere[$row['data']][$row['id']] = array(
                    "da_aquisire" => 0,
                    "CD_inviare" => 0,
                    "CD_firma" => 0,
                    "MEF_inviare" => 0,
                    "MEF_firma" => 0,
                    "SEG_inviare" => 0,
                    "SEG_firma" => 0,
                    "PRE_inviare" => 0,
                    "PRE_firma" => 0,
                    "CC_inviare" => 0,
                    "CC_firma" => 0,
                    "GU_inviare" => 0,
                    "GU_firma" => 0
                );
            }


            //Analisi delle fasi
            $arrayDelibere[$row['data']][$row['id']]['analisi_nr'] = $row['numero'];
            $arrayDelibere[$row['data']][$row['id']]['analisi_consegna'] = round(differenzaDate($row['data'], $row['data_consegna'], g));
            $arrayDelibere[$row['data']][$row['id']]['analisi_cd'] = round(differenzaDate($row['data_direttore_invio'], $row['data_direttore_ritorno'], g));
            if ($row['data_mef_pec'] == null || $row['data_mef_pec'] == "" || $row['data_mef_pec'] == "0000-00-00") {
                $arrayDelibere[$row['data']][$row['id']]['analisi_mef'] = round(differenzaDate($row['data_mef_invio'], $row['data_mef_ritorno'], g));
            } else {
                $arrayDelibere[$row['data']][$row['id']]['analisi_mef'] = round(differenzaDate($row['data_mef_pec'], $row['data_mef_ritorno'], g));
            }
            $arrayDelibere[$row['data']][$row['id']]['analisi_seg'] = round(differenzaDate($row['data_segretario_invio'], $row['data_segretario_ritorno'], g));
            $arrayDelibere[$row['data']][$row['id']]['analisi_pre'] = round(differenzaDate($row['data_presidente_invio'], $row['data_presidente_ritorno'], g));
            $arrayDelibere[$row['data']][$row['id']]['analisi_cc'] = round(differenzaDate($row['data_invio_cc'], $row['data_registrazione_cc'], g));
            $arrayDelibere[$row['data']][$row['id']]['analisi_gu'] = round(differenzaDate($row['data_invio_gu'], $row['data_gu'], g));



            //statistica
            $arrayDelibere[$row['data']][$row['id']]['statistica_arrivo'] = round(differenzaDate($row['data'], $row['data_consegna'], g));
            $arrayDelibere[$row['data']][$row['id']]['statistica_cd_invio_giorni'] = round(differenzaDate($row['data_consegna'], $row['data_direttore_invio'], g));
            $arrayDelibere[$row['data']][$row['id']]['statistica_cd_invio_giorni_tot'] = round(differenzaDate($row['data'], $row['data_direttore_invio'], g));
            $arrayDelibere[$row['data']][$row['id']]['statistica_cd_ritorno_giorni'] = round(differenzaDate($row['data_direttore_invio'], $row['data_direttore_ritorno'], g));
            $arrayDelibere[$row['data']][$row['id']]['statistica_cd_ritorno_giorni_tot'] = round(differenzaDate($row['data'], $row['data_direttore_ritorno'], g));


            if ($row['data_mef_pec'] == null || $row['data_mef_pec'] == "" || $row['data_mef_pec'] == "0000-00-00") {
                $arrayDelibere[$row['data']][$row['id']]['statistica_mef_invio_giorni'] = round(differenzaDate($row['data_direttore_ritorno'], $row['data_mef_invio'], g));
                $arrayDelibere[$row['data']][$row['id']]['statistica_mef_invio_giorni_tot'] = round(differenzaDate($row['data'], $row['data_mef_invio'], g));
                $arrayDelibere[$row['data']][$row['id']]['statistica_mef_ritorno_giorni'] = round(differenzaDate($row['data_mef_invio'], $row['data_mef_ritorno'], g));
                $arrayDelibere[$row['data']][$row['id']]['statistica_mef_ritorno_giorni_tot'] = round(differenzaDate($row['data'], $row['data_mef_ritorno'], g));
            } else {
                $arrayDelibere[$row['data']][$row['id']]['statistica_mef_invio_giorni'] = round(differenzaDate($row['data_direttore_ritorno'], $row['data_mef_pec'], g));
                $arrayDelibere[$row['data']][$row['id']]['statistica_mef_invio_giorni_tot'] = round(differenzaDate($row['data'], $row['data_mef_pec'], g));
                $arrayDelibere[$row['data']][$row['id']]['statistica_mef_ritorno_giorni'] = round(differenzaDate($row['data_mef_pec'], $row['data_mef_ritorno'], g));
                $arrayDelibere[$row['data']][$row['id']]['statistica_mef_ritorno_giorni_tot'] = round(differenzaDate($row['data'], $row['data_mef_ritorno'], g));
            }

            $arrayDelibere[$row['data']][$row['id']]['statistica_seg_invio_giorni'] = round(differenzaDate($row['data_mef_ritorno'], $row['data_segretario_invio'], g));
            $arrayDelibere[$row['data']][$row['id']]['statistica_seg_invio_giorni_tot'] = round(differenzaDate($row['data'], $row['data_segretario_invio'], g));
            $arrayDelibere[$row['data']][$row['id']]['statistica_seg_ritorno_giorni'] = round(differenzaDate($row['data_segretario_invio'], $row['data_segretario_ritorno'], g));
            $arrayDelibere[$row['data']][$row['id']]['statistica_seg_ritorno_giorni_tot'] = round(differenzaDate($row['data'], $row['data_segretario_ritorno'], g));

            $arrayDelibere[$row['data']][$row['id']]['statistica_pre_invio_giorni'] = round(differenzaDate($row['data_segretario_ritorno'], $row['data_presidente_invio'], g));
            $arrayDelibere[$row['data']][$row['id']]['statistica_pre_invio_giorni_tot'] = round(differenzaDate($row['data'], $row['data_presidente_invio'], g));
            $arrayDelibere[$row['data']][$row['id']]['statistica_pre_ritorno_giorni'] = round(differenzaDate($row['data_presidente_invio'], $row['data_presidente_ritorno'], g));
            $arrayDelibere[$row['data']][$row['id']]['statistica_pre_ritorno_giorni_tot'] = round(differenzaDate($row['data'], $row['data_presidente_ritorno'], g));

            $arrayDelibere[$row['data']][$row['id']]['statistica_cc_invio_giorni'] = round(differenzaDate($row['data_presidente_ritorno'], $row['data_registrazione_cc'], g));
            $arrayDelibere[$row['data']][$row['id']]['statistica_cc_invio_giorni_tot'] = round(differenzaDate($row['data'], $row['data_invio_cc'], g));
            $arrayDelibere[$row['data']][$row['id']]['statistica_cc_ritorno_giorni'] = round(differenzaDate($row['data_invio_cc'], $row['data_registrazione_cc'], g));
            $arrayDelibere[$row['data']][$row['id']]['statistica_cc_ritorno_giorni_tot'] = round(differenzaDate($row['data'], $row['data_registrazione_cc'], g));

            $arrayDelibere[$row['data']][$row['id']]['statistica_gu_invio_giorni'] = round(differenzaDate($row['data_registrazione_cc'], $row['data_gu'], g));
            $arrayDelibere[$row['data']][$row['id']]['statistica_gu_invio_giorni_tot'] = round(differenzaDate($row['data'], $row['data_invio_gu'], g));
            $arrayDelibere[$row['data']][$row['id']]['statistica_gu_ritorno_giorni'] = round(differenzaDate($row['data_invio_gu'], $row['data_gu'], g));
            $arrayDelibere[$row['data']][$row['id']]['statistica_gu_ritorno_giorni_tot'] = round(differenzaDate($row['data'], $row['data_gu'], g));




            //situazione
            if ($row['data_consegna'] == null || $row['data_consegna'] == "" || $row['data_consegna'] == "0000-00-00") {
                $arrayDelibere[$row['data']][$row['id']]['da_aquisire'] = 1;
                continue;
            }
            if ($row['data_direttore_invio'] == null || $row['data_direttore_invio'] == "" || $row['data_direttore_invio'] == "0000-00-00") {
                $arrayDelibere[$row['data']][$row['id']]['CD_inviare'] = 1;
                continue;
            }
            if (($row['data_direttore_ritorno'] == null || $row['data_direttore_ritorno'] == "" || $row['data_direttore_ritorno'] == "0000-00-00")
            && (($row['data_direttore_invio'] != null && $row['data_direttore_invio'] != "" && $row['data_direttore_invio'] != "0000-00-00"))) {
                $arrayDelibere[$row['data']][$row['id']]['CD_firma'] =  1;
                continue;
            }

            if (($row['data_mef_invio'] == null || $row['data_mef_invio'] == "" || $row['data_mef_invio'] == "0000-00-00")
            && ($row['data_mef_pec'] == null || $row['data_mef_pec'] == "" || $row['data_mef_pec'] == "0000-00-00")) {
                $arrayDelibere[$row['data']][$row['id']]['MEF_inviare'] = 1;
                continue;
            }
            if (($row['data_mef_ritorno'] == null || $row['data_mef_ritorno'] == "" || $row['data_mef_ritorno'] == "0000-00-00")
                && (($row['data_mef_invio'] != null && $row['data_mef_invio'] != "" && $row['data_mef_invio'] != "0000-00-00"))
                && (($row['data_mef_pec'] != null && $row['data_mef_pec'] != "" && $row['data_mef_pec'] != "0000-00-00"))) {
                $arrayDelibere[$row['data']][$row['id']]['MEF_firma'] = 1;
                continue;
            }

            if ($row['data_segretario_invio'] == null || $row['data_segretario_invio'] == "" || $row['data_segretario_invio'] == "0000-00-00") {
                $arrayDelibere[$row['data']][$row['id']]['SEG_inviare'] = 1;
                //continue;
            }
            if (($row['data_segretario_ritorno'] == null || $row['data_segretario_ritorno'] == "" || $row['data_segretario_ritorno'] == "0000-00-00")
                && (($row['data_segretario_invio'] != null && $row['data_segretario_invio'] != "" && $row['data_segretario_invio'] != "0000-00-00"))) {
                $arrayDelibere[$row['data']][$row['id']]['SEG_firma'] = 1;
                //continue;
            }

            if ($row['data_presidente_invio'] == null || $row['data_presidente_invio'] == "" || $row['data_presidente_invio'] == "0000-00-00") {
                $arrayDelibere[$row['data']][$row['id']]['PRE_inviare'] = 1;
                continue;
            }
            if (($row['data_presidente_ritorno'] == null || $row['data_presidente_ritorno'] == "" || $row['data_presidente_ritorno'] == "0000-00-00")
                && (($row['data_presidente_invio'] != null && $row['data_presidente_invio'] != "" && $row['data_presidente_invio'] != "0000-00-00"))) {
                $arrayDelibere[$row['data']][$row['id']]['PRE_firma'] = 1;
                continue;
            }

            if ($row['data_invio_cc'] == null || $row['data_invio_cc'] == "" || $row['data_invio_cc'] == "0000-00-00") {
                $arrayDelibere[$row['data']][$row['id']]['CC_inviare'] = 1;
                continue;
            }
            if (($row['data_registrazione_cc'] == null || $row['data_registrazione_cc'] == "" || $row['data_registrazione_cc'] == "0000-00-00")
                && (($row['data_invio_cc'] != null && $row['data_invio_cc'] != "" && $row['data_invio_cc'] != "0000-00-00"))) {
                $arrayDelibere[$row['data']][$row['id']]['CC_firma'] = 1;
                continue;
            }

            if ($row['data_invio_gu'] == null || $row['data_invio_gu'] == "" || $row['data_invio_gu'] == "0000-00-00") {
                $arrayDelibere[$row['data']][$row['id']]['GU_inviare'] = 1;
                continue;
            }
            if (($row['data_gu'] == null || $row['data_gu'] == "" || $row['data_gu'] == "0000-00-00")
                && (($row['data_invio_gu'] != null && $row['data_invio_gu'] != "" && $row['data_invio_gu'] != "0000-00-00"))) {
                $arrayDelibere[$row['data']][$row['id']]['GU_firma'] = 1;
                continue;
            }








        }
    }


    echo "<pre>";
    print_r($arrayDelibere);
    echo "</pre>";

}






?>