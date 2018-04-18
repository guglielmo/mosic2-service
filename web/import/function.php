<?php




function setCorteDeiContiDelibere()
{
    global $db;

    $query = "SELECT * 
              FROM DatiDelibera";
    $res = mysqli_query($db, $query);
    if (!$res) {
        return mysqli_error($db);
    }

    //svuoto la tabella
    $query = 'TRUNCATE msc_delibere_cc';
    mysqli_query($db, $query);

    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {

            if ($row['Data_RilievoCC'] != "" && $row['Data_RilievoCC'] != "0000-00-00 00:00:00" && $row['Data_RilievoCC'] != null) {
//                $array_temp = explode("/", $row['Data_RilievoCC']);
//                $data_ok = $array_temp[2] . "-" . $array_temp[1] . "-" . $array_temp[0];
//                $query2 = 'UPDATE `TABLE_delibere_full` SET `Data_RilievoCC`= "' . $data_ok . '"  WHERE Codice_Delibera = "' . $row['Codice_Delibera'] . '"';
//                echo $query2 . "<br>";
//                $res2 = mysqli_query($db, $query2);
                $Nota_RilievoCC = str_replace('"','', $row['Nota_RilievoCC']);
                if ($row['Giorni_RilievoCC'] == 0) { $giorni_rilievo = null;} else {$giorni_rilievo = $row['Giorni_RilievoCC']; }
                $query2 = 'INSERT INTO msc_delibere_cc SET id_delibere = "' . $row['Codice_Delibera'] . '", 
                                                           tipo_documento = "' . $row['Tipo_DocumentoCC'] . '",
                                                           data_rilievo = "' . $row['Data_RilievoCC'] . '",
                                                           numero_rilievo = "' . $row['Numero_RilievoCC'] . '",
                                                           data_risposta = "' . $row['Data_RispostaCC'] . '",
                                                           numero_risposta = "' . $row['Numero_RispostaCC'] . '",
                                                           giorni_rilievo = "' . $giorni_rilievo . '",
                                                           tipo_rilievo = "' . $row['Tipo_Rilievo'] . '",
                                                           note_rilievo = "' . $Nota_RilievoCC . '"
                ';
                //echo $query2 . "<br>";
                $res2 = mysqli_query($db, $query2);
                if (!$res2) {
                    return mysqli_error($db);
                }
            }

            if ($row['Data_RilievoCC2'] != "" && $row['Data_RilievoCC2'] != "0000-00-00 00:00:00" && $row['Data_RilievoCC2'] != null) {
                $Nota_RilievoCC2 = str_replace('"','', $row['Nota_RilievoCC2']);
                if ($row['Giorni_RilievoCC2'] == 0) { $giorni_rilievo = null;} else {$giorni_rilievo = $row['Giorni_RilievoCC2']; }
                $query2 = 'INSERT INTO msc_delibere_cc SET id_delibere = "' . $row['Codice_Delibera'] . '", 
                                                           tipo_documento = "' . $row['Tipo_DocumentoCC2'] . '",
                                                           data_rilievo = "' . $row['Data_RilievoCC2'] . '",
                                                           numero_rilievo = "' . $row['Numero_RilievoCC2'] . '",
                                                           data_risposta = "' . $row['Data_RispostaCC2'] . '",
                                                           numero_risposta = "' . $row['Numero_RispostaCC2'] . '",
                                                           giorni_rilievo = "' . $giorni_rilievo . '",
                                                           tipo_rilievo = "' . $row['Tipo_Rilievo2'] . '",
                                                           note_rilievo = "' . $Nota_RilievoCC2 . '"
                ';
                //echo $query2 . "<br>";
                $res2 = mysqli_query($db, $query2);
                if (!$res2) {
                    return mysqli_error($db);
                }
            }

            if ($row['Data_RilievoCC3'] != "" && $row['Data_RilievoCC3'] != "0000-00-00 00:00:00" && $row['Data_RilievoCC3'] != null) {
                $Nota_RilievoCC3 = str_replace('"','', $row['Nota_RilievoCC3']);
                if ($row['Giorni_RilievoCC3'] == 0) { $giorni_rilievo = null;} else {$giorni_rilievo = $row['Giorni_RilievoCC3']; }
                $query2 = 'INSERT INTO msc_delibere_cc SET id_delibere = "' . $row['Codice_Delibera'] . '", 
                                                           tipo_documento = "' . $row['Tipo_DocumentoCC3'] . '",
                                                           data_rilievo = "' . $row['Data_RilievoCC3'] . '",
                                                           numero_rilievo = "' . $row['Numero_RilievoCC3'] . '",
                                                           data_risposta = "' . $row['Data_RispostaCC3'] . '",
                                                           numero_risposta = "' . $row['Numero_RispostaCC3'] . '",
                                                           giorni_rilievo = "' . $giorni_rilievo . '",
                                                           tipo_rilievo = "' . $row['Tipo_Rilievo3'] . '",
                                                           note_rilievo = "' . $Nota_RilievoCC3 . '"
                ';
                //echo $query2 . "<br>";
                $res2 = mysqli_query($db, $query2);
                if (!$res2) {
                    return mysqli_error($db);
                }
            }

        }
    }

    return;
}

function setUfficiDelibere()
{
    global $db;

    $query = "SELECT * 
              FROM DatiDelibera";
    $res = mysqli_query($db, $query);
    if (!$res) {
        return mysqli_error($db);
    }
    
    //svuoto la tabella
    $query = 'TRUNCATE msc_rel_uffici_delibere';
    mysqli_query($db, $query);

    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {

            if ($row['Codice_Ufficio'] != "") {
                $query2 = 'INSERT INTO msc_rel_uffici_delibere SET id_delibere = "' . $row['Codice_Delibera'] . '", id_uffici = "' . $row['Codice_Ufficio'] . '" ';
                //echo $query2 . "<br>";
                $res2 = mysqli_query($db, $query2);
                if (!$res2) {
                    return mysqli_error($db);
                }
            }

            if ($row['Codice_Ufficio2'] != "") {
                $query2 = 'INSERT INTO msc_rel_uffici_delibere SET id_delibere = "' . $row['Codice_Delibera'] . '", id_uffici = "' . $row['Codice_Ufficio2'] . '" ';
                //echo $query2 . "<br>";
                $res2 = mysqli_query($db, $query2);
                if (!$res2) {
                    return mysqli_error($db);
                }
            }

            if ($row['Codice_Ufficio3'] != "") {
                $query2 = 'INSERT INTO msc_rel_uffici_delibere SET id_delibere = "' . $row['Codice_Delibera'] . '", id_uffici = "' . $row['Codice_Ufficio3'] . '" ';
                //echo $query2 . "<br>";
                $res2 = mysqli_query($db, $query2);
                if (!$res2) {
                    return mysqli_error($db);
                }
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
                if (!$res) {
                    return mysqli_error($db);
                }
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

                    //echo $filename . " ------>>>> " . $explod_path[4] . " | " . $explod_path[5] . " ----- anno: " . $anno . " ----- numero: " . $numero . " ----- Delibera: " . $idDelibera . " ----- " .$tipo. "<br>";


                    if ($idDelibera != "non trovato" && $explod_path[5] != ".ds_store" && $explod_path[5] != ".DS_STORE") {
                        $query = "INSERT INTO `msc_allegati`(`data`, `file`) VALUES (NOW(),'" . $filePath . $filename . "')";
                        //echo $query . "<br>";
                        $res2 = mysqli_query($db, $query);
                        if (!$res2) {
                            return mysqli_error($db) . " (msc_allegati)";
                        }

                        $last_id = mysqli_insert_id($db); //ultimo id inserito

                        $query2 = "INSERT INTO `msc_rel_allegati_delibere`(`id_delibere`, `id_allegati`, `tipo`) VALUES ($idDelibera, $last_id, '$tipo')";
                        //echo $query2 . "<br>";
                        $res2 = mysqli_query($db, $query2);
                        if (!$res2) {
                            return mysqli_error($db) . " (msc_rel_allegati_delibere)";
                        }
                    } else {
                        //echo "ERRORE (non trovato): " . $filename . " ---> " . $explod_path[4] . "<br>";
                    }



                }




            }

        }

    }



    return;

}



function setAllegatiDelibereMEF_RILIEVI($filePath, $path)
{
    global $db;


    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path)) as $filename) {
        if (!is_dir($filename)) {
            $filename = str_replace("../", "", $filename);
            $explod_path = explode("/", $filename);
//          echo"<pre>";
//          print_r($explod_path);
//          echo"</pre>";
            $cartellaDelibera = $explod_path[3];
            $annoDelibera = "20" . substr($explod_path[3], 1, 2);
            $numeroDelibera = (int)substr($explod_path[3], 3, 7);

            if ($explod_path[4] != "versioni") {
                //echo $filename . " ------>>>> " . $explod_path[4] . " ----- anno: " . $annoDelibera . " ----- numero: " . $numeroDelibera . "<br>";

                //ricavo la delibera da ANNO e NUMERO
                $query = "SELECT * FROM msc_delibere WHERE YEAR(data) = '$annoDelibera' AND numero = '$numeroDelibera'";
                $res = mysqli_query($db, $query);
                if (!$res) {
                    return mysqli_error($db) . 'msc_delibere';
                }
                $idDelibera = "non trovato";
                if (mysqli_num_rows($res) >= 1) {
                    while ($row = mysqli_fetch_array($res)) {
                        $idDelibera = $row['id']; // id della delibera
                    }
                }

                if ($idDelibera != "non trovato" && $explod_path[4] != ".ds_store" && $explod_path[4] != ".DS_STORE") {
                    $query = "INSERT INTO `msc_allegati`(`data`, `file`) VALUES (NOW(),'" . $filePath . $filename . "')";
                    //echo $query . "<br>";
                    $res2 = mysqli_query($db, $query);
                    if (!$res2) {
                        return mysqli_error($db) . " (msc_allegati)";
                    }

                    $last_id = mysqli_insert_id($db); //ultimo id inserito

                    $query2 = "INSERT INTO `msc_rel_allegati_delibere`(`id_delibere`, `id_allegati`, `tipo`) VALUES ($idDelibera, $last_id, '$explod_path[2]')";
                    //echo $query2 . "<br>";
                    $res2 = mysqli_query($db, $query2);
                    if (!$res2) {
                        return mysqli_error($db) . " (msc_rel_allegati_delibere)";
                    }
                } else {
                    //echo "ERRORE (non trovato): " . $filename . " ---> " . $explod_path[4] . "<br>";
                }
            }

        }
    }

    return;
}



function setFunzionariDelibere()
{
    global $db;

    $query = "SELECT * 
              FROM DatiDelibera";
    $res = mysqli_query($db, $query);
    if (!$res) {
        return mysqli_error($db);
    }

    //svuoto la tabella
    $query = 'TRUNCATE msc_rel_firmatari_delibere';
    mysqli_query($db, $query);


    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {

            if ($row['Codice_Funzionario'] != "") {
                $query2 = 'INSERT INTO msc_rel_firmatari_delibere SET id_delibere = "' . $row['Codice_Delibera'] . '", id_firmatari = "' . $row['Codice_Funzionario'] . '" ';
                //echo $query2 . "<br>";
                $res2 = mysqli_query($db, $query2);
                if (!$res2) {
                    return mysqli_error($db);
                }
            }

            if ($row['Codice_Funzionario2'] != "") {
                $query2 = 'INSERT INTO msc_rel_firmatari_delibere SET id_delibere = "' . $row['Codice_Delibera'] . '", id_firmatari = "' . $row['Codice_Funzionario2'] . '" ';
                //echo $query2 . "<br>";
                $res2 = mysqli_query($db, $query2);
                if (!$res2) {
                    return mysqli_error($db);
                }
            }

            if ($row['Codice_Funzionario3'] != "") {
                $query2 = 'INSERT INTO msc_rel_firmatari_delibere SET id_delibere = "' . $row['Codice_Delibera'] . '", id_firmatari = "' . $row['Codice_Funzionario3'] . '" ';
                //echo $query2 . "<br>";
                $res2 = mysqli_query($db, $query2);
                if (!$res2) {
                    return mysqli_error($db);
                }
            }

        }
    }

    return;
}


function setDelibere()
{
    global $db;

    $query = "SELECT * FROM DatiDelibera";
    $res = mysqli_query($db, $query);
    if (!$res) {
        return mysqli_error($db);
    }
    //svuoto la tabella
    $query = 'TRUNCATE msc_delibere';
    mysqli_query($db, $query);

    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {


            if ($row['Data_GU'] != "0000-00-00 00:00:00") {
                $situazione = 10; // 9 - Pubblicato Gazzetta Ufficiale
            } elseif ($row['Data_InvioGU'] != "0000-00-00 00:00:00" && $row['Data_GU'] == "0000-00-00 00:00:00") {
                $situazione = 9; // 8 - Alla Gazzetta Ufficiale
            } elseif ($row['Data_InvioCC'] != "0000-00-00 00:00:00" && $row['Data_RegistrazioneCC'] != "0000-00-00 00:00:00") {
                $situazione = 8; // 8 - Registrato dalla corte dei conti
            } elseif ($row['Data_InvioCC'] != "0000-00-00 00:00:00" && $row['Data_RegistrazioneCC'] == "0000-00-00 00:00:00") {
                $situazione = 7; // 7 - Alla Corte dei Conti
            } elseif ($row['Data_Consegna'] != "0000-00-00 00:00:00" && $row['Data_SegretarioInvio'] != "0000-00-00 00:00:00" && $row['Data_SegretarioRitorno'] != "0000-00-00 00:00:00" && $row['Data_PresidenteInvio'] != "0000-00-00 00:00:00" && $row['Data_PresidenteRitorno'] == "0000-00-00 00:00:00") {
                $situazione = 6; // 6 - In firma Presidente Cipe
            } elseif ($row['Data_Consegna'] != "0000-00-00 00:00:00" && $row['Data_SegretarioInvio'] != "0000-00-00 00:00:00" && $row['Data_SegretarioRitorno'] == "0000-00-00 00:00:00") {
                $situazione = 5; // 5 - In firma Segretario Cipe
            } elseif ($row['Data_MefRitorno'] != "0000-00-00 00:00:00") {
                $situazione = 4; // 4 - Ritorno MEF
            } elseif ($row['Data_MefInvio'] != "0000-00-00 00:00:00") {
                $situazione = 3; // 3 - Invio MEF
            } elseif ($row['Data_Consegna'] != "0000-00-00 00:00:00" && $row['Data_SegretarioInvio'] == "0000-00-00 00:00:00" && $row['Data_PresidenteInvio'] == "0000-00-00 00:00:00") {
                $situazione = 2; // 2 - In lavorazione
            } elseif ($row['Data_Consegna'] == "0000-00-00 00:00:00") {
                $situazione = 1; // 1 - Da acquisire
            } else {
                $situazione = null;
            }


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
                    `note_gu`,
                    `situazione`
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
                    '". addslashes($row['Nota_GU']) ."',
                    '". $situazione ."'
                    
            )";

            //echo $query2 . "<br>";
            $res2 = mysqli_query($db, $query2);
            if (!$res2) {
                return mysqli_error($db);
            }
        }
    }

    return;
}





function createTitolari() {
    global $db;
    
    $query = "SELECT * FROM Titolario";
    $res = mysqli_query($db, $query);
    if (!$res) {
        return mysqli_error($db);
    }

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
            if (!$res2) {
                return mysqli_error($db);
            }
        }
    }

    return;
}

function createMittenti() {
    global $db;

    $query = "SELECT * FROM Registro GROUP BY Mittente_Registro";
    $res = mysqli_query($db, $query);
    if (!$res) {
        return mysqli_error($db);
    }

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
                                                    "' . str_replace('"','',$row['Mittente_Registro']) . '"
                                                    )';
                //echo $query . "<br>";
                $res2 = mysqli_query($db, $query);
                if (!$res2) {
                    return mysqli_error($db);
                }
            }
        }
    }

    return;
}

function createAmministrazioni() {
    global $db;

    $query = "SELECT * FROM Amministrazioni ORDER BY Codice_Amministrazione";
    $res = mysqli_query($db, $query);
    if (!$res) {
        return mysqli_error($db);
    }

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
            $res2 = mysqli_query($db, $query);
            if (!$res2) {
                return mysqli_error($db);
            }
        }
    }

    return;
}


function createArgomenti() {
    global $db;
    
    $query = "SELECT * FROM ArgomentiCipe";
    $res = mysqli_query($db, $query);
    if (!$res) {
        return mysqli_error($db);
    }

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
            if (!$res2) {
                return mysqli_error($db);
            }
        }
    }

    return;
}

function createUffici() {
    global $db;
    
    $query = "SELECT * FROM UfficiDipe";
    $res = mysqli_query($db, $query);
    if (!$res) {
        return mysqli_error($db);
    }

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
            if (!$res2) {
                return mysqli_error($db);
            }
        }
    }

    return;
}


function createRisultanze() {
    global $db;
    
    $query = "SELECT * FROM Risultanze_PreCipe";
    $res = mysqli_query($db, $query);
    if (!$res) {
        return mysqli_error($db);
    }

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
            if (!$res2) {
                return mysqli_error($db);
            }
        }
    }
    return;
}

function createTipoArgomentiCipe() {
    global $db;
    
    $query = "SELECT * FROM TipoArgomentiCipe";
    $res = mysqli_query($db, $query);
    if (!$res) {
        return mysqli_error($db);
    }

    //svuoto la tabella
    $query2 = 'TRUNCATE msc_argomenti_tipo_cipe';
    mysqli_query($db, $query2);

    //echo mysqli_num_rows($res);

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
            //echo $query . "<br>";
            $res2 = mysqli_query($db, $query);

            if (!$res2) {
                return mysqli_error($db);
            }
        }
    }

    return;
}

function createTipoEsitiCipe() {
    global $db;

    $query = "SELECT * FROM TipoEsitiCipe";
    $res = mysqli_query($db, $query);
    if (!$res) {
        return mysqli_error($db);
    }

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
            //echo $query . "<br>";
            $res2 = mysqli_query($db, $query);
            if (!$res2) {
                return mysqli_error($db);
            }
        }
    }

    return;
}

function createFirmatari() {
    global $db;
    
    $query = "SELECT * FROM Firmatari WHERE Codice_Firmatario <> 0 ORDER BY `Codice_Firmatario` ASC ";
    $res = mysqli_query($db, $query);
    if (!$res) {
        return mysqli_error($db);
    }
    //svuoto la tabella
    $query2 = 'TRUNCATE msc_firmatari';
    mysqli_query($db, $query2);

    //popolo la tabella
    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {
                $Disattivato_Firmatario = 0;
                if ($row['Disattivato_Firmatario'] == 'TRUE') {
                    $Disattivato_Firmatario = 1;
                }
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
                                                    "' . $Disattivato_Firmatario . '"
                                                    )';
                //echo $query . ";<br>";
                $res2 = mysqli_query($db, $query);
                if (!$res2) {
                    return mysqli_error($db);
                }
        }

    }

    return;
}

function createRuoliCipe() {
    global $db;
    
    $query = "SELECT * FROM Livelli";
    $res = mysqli_query($db, $query);
    if (!$res) {
        return mysqli_error($db);
    }

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
                //echo $query . "<br>";
                $res2 = mysqli_query($db, $query);
                if (!$res2) {
                    return mysqli_error($db);
                }
        }
    }

    return;
}

function createLastUpdates() {
    global $db;
    
    
    //svuoto la tabella
    $query = 'TRUNCATE msc_last_updates';
    mysqli_query($db, $query);

    $queryLastUpdates = "INSERT INTO `msc_last_updates` (`id`, `tabella`, `lastUpdate`) VALUES
                (1, 'titolari', NOW()),
                (2, 'fascicoli', NOW()),
                (3, 'registri', NOW()),
                (4, 'amministrazioni', NOW()),
                (5, 'mittenti', NOW()),
                (6, 'tags', NOW()),
                (7, 'uffici', NOW()),
                (8, 'ruoli_cipe', NOW()),
                (9, 'groups', NOW()),
                (10, 'argomenti', NOW()),
                (11, 'precipe', NOW()),
                (12, 'precipeodg', NOW()),
                (13, 'firmatari', NOW()),
                (14, 'cipe', NOW()),
                (15, 'cipeodg', NOW()),
                (16, 'firmataritipo', NOW()),
                (17, 'cipeesiti', NOW()),
                (18, 'cipeesititipo', NOW()),
                (19, 'cipeargomentitipo', NOW()),
                (20, 'users', NOW()),
                (21, 'delibere', NOW()),
                (22, 'adempimenti', NOW()),
                (23, 'adempimenti_soggetti', NOW()),
                (24, 'adempimenti_ambiti', NOW()),
                (25, 'adempimenti_azioni', NOW()),
                (26, 'adempimenti_tipologie', NOW()),
                (27, 'adempimenti_scadenze', NOW()),
                (28, 'monitor', NOW()),
                (29, 'monitor_group', NOW());";
    $resLastUpdates = mysqli_query($db, $queryLastUpdates);
    if (!$resLastUpdates) { return mysqli_error($db); }

    return;
}


function createTipoFirmatari() {
    global $db;
    
    $query = "SELECT * FROM TipoFirmatari";
    $res = mysqli_query($db, $query);
    if (!$res) {
        return mysqli_error($db);
    }
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
                //echo $query . "<br>";
                $res2 = mysqli_query($db, $query);
                if (!$res2) {
                    return mysqli_error($db);
                }
            }
        }
    }
    return;
}

//function createAdempimenti() {
//    global $db;
//
//    $query = "SELECT * FROM Adempimenti";
//    $res = mysqli_query($db, $query);
//    if (!$res) {
//        return mysqli_error($db);
//    }
//    //svuoto la tabella
//    $query = 'TRUNCATE msc_adempimenti';
//    mysqli_query($db, $query);
//
//    //popolo la tabella
//    if (mysqli_num_rows($res) >= 1) {
//        while ($row = mysqli_fetch_array($res)) {
//
//            //ricavo l'id dell'utente
//            $query2 = 'SELECT * FROM fos_user WHERE username = "'. $row['Utente_Modifica'] . '"';
//            $res2 = mysqli_query($db, $query2);
//            if (!$res2) {
//                return mysqli_error($db);
//            }
//
//            $idUtente = 0;
//            if (mysqli_num_rows($res2) >= 1) {
//                while ($row2 = mysqli_fetch_array($res2)) {
//                    $idUtente = $row2['id'];
//                }
//            }
//
//            $Descrizione_Adempimento = str_replace('"','',$row['Descrizione_Adempimento']);
//            $Descrizione_Adempimento = str_replace('“','',$Descrizione_Adempimento);
//            $Descrizione_Adempimento = str_replace('”','',$Descrizione_Adempimento);
//            $Note_Adempimento = str_replace('"','',$row['Note_Adempimento']);
//            $Note_Adempimento = str_replace('“','',$Note_Adempimento);
//            $Note_Adempimento = str_replace('”','',$Note_Adempimento);
//
//            $query = 'INSERT INTO `msc_adempimenti`(
//                                                 `codice`,
//                                                 `progressivo`,
//                                                 `codice_scheda`,
//                                                 `id_delibere`,
//                                                 `descrizione`,
//                                                 `codice_descrizione`,
//                                                 `codice_fonte`,
//                                                 `codice_esito`,
//                                                 `data_scadenza`,
//                                                 `giorni_scadenza`,
//                                                 `mesi_scadenza`,
//                                                 `anni_scadenza`,
//                                                 `vincolo`,
//                                                 `note`,
//                                                 `utente`,
//                                                 `data_modifica`
//                                                )
//                                                VALUES (
//                                                "' . $row['Codice_Adempimento'] . '",
//                                                "' . $row['Progressivo_Adempimento'] . '",
//                                                "' . $row['Codice_Scheda'] . '",
//                                                "' . $row['Codice_Delibera'] . '",
//                                                "' . $Descrizione_Adempimento . '",
//                                                "' . $row['Codice_DescrizioneAdempimento'] . '",
//                                                "' . $row['Codice_FonteAdempimento'] . '",
//                                                "' . $row['Codice_EsitoAdempimento'] . '",
//                                                "' . $row['Data_Scadenza_Adempimento'] . '",
//                                                "' . $row['Giorni_Scadenza_Adempimento'] . '",
//                                                "' . $row['Mesi_Scadenza_Adempimento'] . '",
//                                                "' . $row['Anni_Scadenza_Adempimento'] . '",
//                                                "' . $row['Vincolo_Adempimento'] . '",
//                                                "' . $Note_Adempimento . '",
//                                                "' . $idUtente . '",
//                                                "' . $row['Data_UtenteModifica'] . '"
//                                                )';
//            //echo $query . "<br>";
//            $res3 = mysqli_query($db, $query);
//            if (!$res3) {
//                return mysqli_error($db);
//            }
//        }
//    }
//
//    return;
//}

function createAdempimenti() {
    global $db;

    $query = "SELECT * FROM Adempimenti";
    $res = mysqli_query($db, $query);
    if (!$res) {
        return mysqli_error($db);
    }
    //svuoto la tabella
    $query = 'TRUNCATE msc_adempimenti';
    mysqli_query($db, $query);
    $query = 'TRUNCATE msc_rel_amministrazioni_adempimenti';
    mysqli_query($db, $query);

    //popolo la tabella
    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {

            //ricavo l'id dell'ambito
            $query2 = 'SELECT * FROM AdempimentiAmbiti WHERE denominazione = "'. $row['Ambito'] . '"';
            $res2 = mysqli_query($db, $query2);
            if (!$res2) { return mysqli_error($db) . " (AdempimentiAmbiti)"; }
            while ($row2 = mysqli_fetch_array($res2)) {
                $id_ambito = $row2['id'];
            }
            //ricavo l'id della tipologia
            $query2 = 'SELECT * FROM AdempimentiTipologie WHERE denominazione = "'. $row['Tipologia'] . '"';
            $res2 = mysqli_query($db, $query2);
            if (!$res2) { return mysqli_error($db) . " (AdempimentiTipologie)"; }
            while ($row2 = mysqli_fetch_array($res2)) {
                $id_tipologia = $row2['id'];
            }
            //ricavo l'id dell' azione
            if ($row['Azione'] == "Cominicazione") { $row['Azione'] = "Comunicazione"; }
            $query2 = 'SELECT * FROM AdempimentiAzioni WHERE denominazione = "'. $row['Azione'] . '"';
            $res2 = mysqli_query($db, $query2);
            if (!$res2) { return mysqli_error($db) . " (AdempimentiTipologie)"; }
            while ($row2 = mysqli_fetch_array($res2)) {
                $id_azione = $row2['id'];
            }


            //ricavo l'id dell' ufficio (struttura)
            $struttura = $row['Struttura'];
            if( strpos( $struttura, "Ufficio II" ) !== false && $row['Struttura'] != "Ufficio III") {
                $struttura = "Ufficio II";
            }
            if( strpos( $struttura, "Ufficio V" ) !== false ) {
                $struttura = "Ufficio V";
            }
            $query2 = 'SELECT * FROM msc_uffici WHERE denominazione = "'. $struttura . '"';
            $res2 = mysqli_query($db, $query2);
            if (!$res2) { return mysqli_error($db) . " (msc_uffici)"; }
            while ($row2 = mysqli_fetch_array($res2)) {
                $id_struttura = $row2['id'];
            }




            //ricavo l'id della delibera (da numero_delibera e suduta)
            $query_delibere = 'SELECT * FROM msc_delibere WHERE data = "' . $row['Seduta'] . '" AND numero = "'.$row['Numero_Delibera'].'"';
            $res_delibere = mysqli_query($db, $query_delibere);
            if (!$res_delibere) {
                return mysqli_error($db) . " (msc_delibere)";
            }
            //echo $query_delibere . "<br>";

            while ($row_delibere = mysqli_fetch_array($res_delibere)) {
                $delibera = $row_delibere['id'];
            }

            //ricavo l'id delle amministrazioni adempimenti
            $query2 = 'SELECT Progressivo, Amministrazione FROM Adempimenti WHERE Progressivo = "'. $row['Progressivo'] . '"';
            $res2 = mysqli_query($db, $query2);
            if (!$res2) { return mysqli_error($db) . " (Adempimenti - relazione)"; }
            while ($row2 = mysqli_fetch_array($res2)) {
                $row['Amministrazione'] = str_replace("  ","",$row['Amministrazione']);
                $arrayTemp = explode("|", $row['Amministrazione']);
                foreach ($arrayTemp as $item) {

                    $query3 = 'SELECT id FROM msc_adempimenti_amministrazioni WHERE denominazione = "'. $item . '"';
                    $res3 = mysqli_query($db, $query3);
                    if (!$res3) { return mysqli_error($db) . " (msc_adempimenti_amministrazioni)"; }
                    while ($row3 = mysqli_fetch_array($res3)) {
                        $id_amministrazione = $row3['id'];
                    }

                    $query = 'INSERT INTO `msc_rel_amministrazioni_adempimenti`(
                                                 `id_adempimenti`,
                                                 `id_amministrazioni`
                                                )
                                                VALUES (
                                                "' . $row['Progressivo'] . '",
                                                "' . $id_amministrazione . '"
                                                )';
                    $res3 = mysqli_query($db, $query);
                    if (!$res3) {
                        return mysqli_error($db) . " (msc_rel_amministrazioni_adempimenti)";
                    }
                }
            }

            $query = 'INSERT INTO `msc_adempimenti`(
                                                 `id`,
                                                 `istruttore`,
                                                 `id_delibere`,
                                                 `numero_delibera`,
                                                 `anno`,
                                                 `seduta`,
                                                 `materia`,
                                                 `argomento`,
                                                 `fondo_norma`,
                                                 `ambito`,
                                                 `localizzazione`,
                                                 `cup`,
                                                 `riferimento`,
                                                 `descrizione`,
                                                 `tipologia`,
                                                 `azione`,
                                                 `mancato_assolvimento`,
                                                 `norme_delibere`,
                                                 `data_scadenza`,
                                                 `destinatario`,
                                                 `struttura`,
                                                 `adempiuto`,
                                                 `periodicita`,
                                                 `pluriennalita`,
                                                 `note`
                                                )
                                                VALUES (
                                                "' . $row['Progressivo'] . '",
                                                "' . $row['Istruttore'] . '",
                                                "' . $delibera . '",
                                                "' . $row['Numero_Delibera'] . '",
                                                "' . $row['Anno'] . '",
                                                "' . $row['Seduta'] . '",
                                                "' . $row['Materia'] . '",
                                                "' . $row['Argomento'] . '",
                                                "' . $row['Fondo_norma'] . '",
                                                "' . $id_ambito . '",
                                                "' . $row['Localizzazione'] . '",
                                                "' . $row['CUP'] . '",
                                                "' . $row['Riferimento'] . '",
                                                "' . $row['Descrizione'] . '",
                                                "' . $id_tipologia . '",
                                                "' . $id_azione . '",
                                                "' . $row['Mancato_assolvimento'] . '",
                                                "' . $row['Norme_delibere'] . '",
                                                "' . $row['Scadenza'] . '",
                                                "' . $row['Destinatario'] . '",
                                                "' . $id_struttura . '",
                                                "' . $row['Adempiuto'] . '",
                                                "' . $row['Periodicita'] . '",
                                                "' . $row['Pluriennalita'] . '",
                                                "' . $row['NOTE'] . '"
                                                )';
            //echo $query . "<br>";
            $res3 = mysqli_query($db, $query);
            if (!$res3) {
                return mysqli_error($db) . " (msc_adempimenti)";
            }
        }
    }

    return;
}


function createAdempimentiAmbiti() {
    global $db;

    $query = "SELECT * FROM AdempimentiAmbiti";
    $res = mysqli_query($db, $query);
    if (!$res) {
        return mysqli_error($db);
    }
    //svuoto la tabella
    $query = 'TRUNCATE msc_adempimenti_ambiti';
    mysqli_query($db, $query);

    //popolo la tabella
    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {
            $query = 'INSERT INTO `msc_adempimenti_ambiti`(
                                                 `id`,
                                                 `denominazione`,
                                                 `note`
                                                )
                                                VALUES (
                                                "' . $row['id'] . '",
                                                "' . $row['denominazione'] . '",
                                                "' . $row['note'] . '"
                                                )';
            $res3 = mysqli_query($db, $query);
            if (!$res3) {
                return mysqli_error($db) . " (msc_adempimenti_ambiti)";
            }
        }
    }

    return;
}

function createAdempimentiAzioni() {
    global $db;

    $query = "SELECT * FROM AdempimentiAzioni";
    $res = mysqli_query($db, $query);
    if (!$res) {
        return mysqli_error($db);
    }
    //svuoto la tabella
    $query = 'TRUNCATE msc_adempimenti_azioni';
    mysqli_query($db, $query);

    //popolo la tabella
    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {
            $query = 'INSERT INTO `msc_adempimenti_azioni`(
                                                 `id`,
                                                 `denominazione`,
                                                 `note`
                                                )
                                                VALUES (
                                                "' . $row['id'] . '",
                                                "' . $row['denominazione'] . '",
                                                "' . $row['note'] . '"
                                                )';
            $res3 = mysqli_query($db, $query);
            if (!$res3) {
                return mysqli_error($db) . " (msc_adempimenti_azioni)";
            }
        }
    }

    return;
}

function createAdempimentiTipologie() {
    global $db;

    $query = "SELECT * FROM AdempimentiTipologie";
    $res = mysqli_query($db, $query);
    if (!$res) {
        return mysqli_error($db);
    }
    //svuoto la tabella
    $query = 'TRUNCATE msc_adempimenti_tipologie';
    mysqli_query($db, $query);

    //popolo la tabella
    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {
            $query = 'INSERT INTO `msc_adempimenti_tipologie`(
                                                 `id`,
                                                 `denominazione`,
                                                 `note`
                                                )
                                                VALUES (
                                                "' . $row['id'] . '",
                                                "' . $row['denominazione'] . '",
                                                "' . $row['note'] . '"
                                                )';
            $res3 = mysqli_query($db, $query);
            if (!$res3) {
                return mysqli_error($db) . " (msc_adempimenti_tipologie)";
            }
        }
    }

    return;
}

function createAdempimentiAmministrazioni() {
    global $db;

    $query = "SELECT Amministrazione FROM Adempimenti GROUP BY Amministrazione";
    $res = mysqli_query($db, $query);
    if (!$res) {
        return mysqli_error($db);
    }
    //svuoto la tabella
    $query = 'TRUNCATE msc_adempimenti_amministrazioni';
    mysqli_query($db, $query);

    $arrayAmministrazioni = array();

    //popolo la tabella
    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {
            $row['Amministrazione'] = str_replace("  ","",$row['Amministrazione']);
            $arrayTemp = explode("|", $row['Amministrazione']);
            foreach ($arrayTemp as $item) {
                if (!in_array($item, $arrayAmministrazioni)) {
                    $arrayAmministrazioni[] = $item;
                }
            }
        }

        foreach ($arrayAmministrazioni as $item) {
            $query = 'INSERT INTO `msc_adempimenti_amministrazioni`(
                                                 `denominazione`,
                                                 `note`
                                                )
                                                VALUES (
                                                "' . $item . '",
                                                ""
                                                )';
            $res3 = mysqli_query($db, $query);
            if (!$res3) {
                return mysqli_error($db) . " (msc_adempimenti_amministrazioni)";
            }
        }
    }

    return;
}


function createUtenti() {
    global $db;
    
    $query = "SELECT * FROM PwUtenti";
    $res = mysqli_query($db, $query);
    if (!$res) {
        return mysqli_error($db);
    }

    //svuoto la tabella
    $query = 'TRUNCATE fos_user';
    mysqli_query($db, $query);

    //svuoto la tabella
    $query = 'TRUNCATE fos_user_group';
    mysqli_query($db, $query);

    //svuoto la tabella
    $query = 'TRUNCATE fos_group';
    mysqli_query($db, $query);


////(3, '3', 'Lettura di tutti i contenuti + scrittura adempimenti', 'a:27:{i:0;s:25:\"ROLE_READ_AMMINISTRAZIONI\";i:1;s:18:\"ROLE_READ_MITTENTI\";i:2;s:18:\"ROLE_READ_TITOLARI\";i:3;s:19:\"ROLE_READ_FASCICOLI\";i:4;s:18:\"ROLE_READ_REGISTRI\";i:5;s:16:\"ROLE_READ_GROUPS\";i:6;s:16:\"ROLE_READ_UFFICI\";i:7;s:20:\"ROLE_READ_RUOLI_CIPE\";i:8;s:14:\"ROLE_READ_TAGS\";i:9;s:17:\"ROLE_READ_PRECIPE\";i:10;s:23:\"ROLE_READ_MONITOR_GROUP\";i:11;s:17:\"ROLE_READ_MONITOR\";i:12;s:21:\"ROLE_READ_ADEMPIMENTI\";i:13;s:18:\"ROLE_READ_DELIBERE\";i:14;s:27:\"ROLE_READ_CIPEARGOMENTITIPO\";i:15;s:23:\"ROLE_READ_CIPEESITITIPO\";i:16;s:19:\"ROLE_READ_CIPEESITI\";i:17;s:23:\"ROLE_READ_FIRMATARITIPO\";i:18;s:14:\"ROLE_READ_CIPE\";i:19;s:19:\"ROLE_READ_FIRMATARI\";i:20;s:20:\"ROLE_READ_PRECIPEODG\";i:21;s:17:\"ROLE_READ_CIPEODG\";i:22;s:31:\"ROLE_READ_AREARISERVATA_PRECIPE\";i:23;s:37:\"ROLE_READ_AREARISERVATA_PRECIPE_CHECK\";i:24;s:28:\"ROLE_READ_AREARISERVATA_CIPE\";i:25;s:34:\"ROLE_READ_AREARISERVATA_CIPE_CHECK\";i:26;s:15:\"ROLE_READ_USERS\";}');";


    $queryGroup = "INSERT INTO `fos_group` (`id`, `codice`, `name`, `roles`) VALUES
(1, '1', 'Amministratore del sistema', 'a:136:{i:0;s:23:\"ROLE_CREATE_ADEMPIMENTI\";i:1;s:23:\"ROLE_DELETE_ADEMPIMENTI\";i:2;s:21:\"ROLE_EDIT_ADEMPIMENTI\";i:3;s:21:\"ROLE_READ_ADEMPIMENTI\";i:4;s:27:\"ROLE_CREATE_AMMINISTRAZIONI\";i:5;s:25:\"ROLE_EDIT_AMMINISTRAZIONI\";i:6;s:25:\"ROLE_READ_AMMINISTRAZIONI\";i:7;s:21:\"ROLE_CREATE_ARGOMENTI\";i:8;s:21:\"ROLE_DELETE_ARGOMENTI\";i:9;s:19:\"ROLE_EDIT_ARGOMENTI\";i:10;s:19:\"ROLE_READ_ARGOMENTI\";i:11;s:16:\"ROLE_CREATE_CIPE\";i:12;s:16:\"ROLE_DELETE_CIPE\";i:13;s:14:\"ROLE_EDIT_CIPE\";i:14;s:14:\"ROLE_READ_CIPE\";i:15;s:19:\"ROLE_CREATE_CIPEODG\";i:16;s:19:\"ROLE_DELETE_CIPEODG\";i:17;s:17:\"ROLE_READ_CIPEODG\";i:18;s:20:\"ROLE_CREATE_DELIBERE\";i:19;s:18:\"ROLE_EDIT_DELIBERE\";i:20;s:18:\"ROLE_READ_DELIBERE\";i:21;s:21:\"ROLE_CREATE_FASCICOLI\";i:22;s:21:\"ROLE_DELETE_FASCICOLI\";i:23;s:19:\"ROLE_EDIT_FASCICOLI\";i:24;s:19:\"ROLE_READ_FASCICOLI\";i:25;s:21:\"ROLE_CREATE_FIRMATARI\";i:26;s:21:\"ROLE_DELETE_FIRMATARI\";i:27;s:19:\"ROLE_EDIT_FIRMATARI\";i:28;s:19:\"ROLE_READ_FIRMATARI\";i:29;s:20:\"ROLE_CREATE_MITTENTI\";i:30;s:18:\"ROLE_EDIT_MITTENTI\";i:31;s:18:\"ROLE_READ_MITTENTI\";i:32;s:20:\"ROLE_DELETE_MITTENTI\";i:33;s:19:\"ROLE_CREATE_PRECIPE\";i:34;s:17:\"ROLE_EDIT_PRECIPE\";i:35;s:17:\"ROLE_READ_PRECIPE\";i:36;s:19:\"ROLE_DELETE_PRECIPE\";i:37;s:22:\"ROLE_CREATE_PRECIPEODG\";i:38;s:20:\"ROLE_READ_PRECIPEODG\";i:39;s:22:\"ROLE_DELETE_PRECIPEODG\";i:40;s:21:\"ROLE_CREATE_RUOLICIPE\";i:41;s:21:\"ROLE_DELETE_RUOLICIPE\";i:42;s:19:\"ROLE_EDIT_RUOLICIPE\";i:43;s:19:\"ROLE_READ_RUOLICIPE\";i:44;s:16:\"ROLE_CREATE_TAGS\";i:45;s:16:\"ROLE_DELETE_TAGS\";i:46;s:14:\"ROLE_EDIT_TAGS\";i:47;s:14:\"ROLE_READ_TAGS\";i:48;s:20:\"ROLE_CREATE_TITOLARI\";i:49;s:20:\"ROLE_DELETE_TITOLARI\";i:50;s:18:\"ROLE_EDIT_TITOLARI\";i:51;s:18:\"ROLE_READ_TITOLARI\";i:52;s:18:\"ROLE_CREATE_UFFICI\";i:53;s:18:\"ROLE_DELETE_UFFICI\";i:54;s:16:\"ROLE_EDIT_UFFICI\";i:55;s:16:\"ROLE_READ_UFFICI\";i:56;s:18:\"ROLE_CREATE_UTENTI\";i:57;s:18:\"ROLE_DELETE_UTENTI\";i:58;s:16:\"ROLE_EDIT_UTENTI\";i:59;s:16:\"ROLE_READ_UTENTI\";i:60;s:21:\"ROLE_CREATE_CIPEESITI\";i:61;s:21:\"ROLE_DELETE_CIPEESITI\";i:62;s:19:\"ROLE_EDIT_CIPEESITI\";i:63;s:19:\"ROLE_READ_CIPEESITI\";i:64;s:25:\"ROLE_CREATE_CIPEESITITIPO\";i:65;s:25:\"ROLE_DELETE_CIPEESITITIPO\";i:66;s:23:\"ROLE_EDIT_CIPEESITITIPO\";i:67;s:23:\"ROLE_READ_CIPEESITITIPO\";i:68;s:29:\"ROLE_CREATE_CIPEARGOMENTITIPO\";i:69;s:29:\"ROLE_DELETE_CIPEARGOMENTITIPO\";i:70;s:27:\"ROLE_EDIT_CIPEARGOMENTITIPO\";i:71;s:27:\"ROLE_READ_CIPEARGOMENTITIPO\";i:72;s:16:\"ROLE_READ_GROUPS\";i:73;s:18:\"ROLE_CREATE_GROUPS\";i:74;s:16:\"ROLE_EDIT_GROUPS\";i:75;s:18:\"ROLE_DELETE_GROUPS\";i:76;s:15:\"ROLE_READ_USERS\";i:77;s:17:\"ROLE_CREATE_USERS\";i:78;s:15:\"ROLE_EDIT_USERS\";i:79;s:17:\"ROLE_DELETE_USERS\";i:80;s:23:\"ROLE_READ_FIRMATARITIPO\";i:81;s:34:\"ROLE_READ_AREARISERVATA_CIPE_CHECK\";i:82;s:28:\"ROLE_READ_AREARISERVATA_CIPE\";i:83;s:37:\"ROLE_READ_AREARISERVATA_PRECIPE_CHECK\";i:84;s:31:\"ROLE_READ_AREARISERVATA_PRECIPE\";i:85;s:20:\"ROLE_READ_RUOLI_CIPE\";i:86;s:22:\"ROLE_CREATE_RUOLI_CIPE\";i:87;s:20:\"ROLE_EDIT_RUOLI_CIPE\";i:88;s:22:\"ROLE_DELETE_RUOLI_CIPE\";i:89;s:33:\"ROLE_DELETE_AREARISERVATA_PRECIPE\";i:90;s:31:\"ROLE_EDIT_AREARISERVATA_PRECIPE\";i:91;s:33:\"ROLE_CREATE_AREARISERVATA_PRECIPE\";i:92;s:39:\"ROLE_CREATE_AREARISERVATA_PRECIPE_CHECK\";i:93;s:37:\"ROLE_EDIT_AREARISERVATA_PRECIPE_CHECK\";i:94;s:39:\"ROLE_DELETE_AREARISERVATA_PRECIPE_CHECK\";i:95;s:30:\"ROLE_DELETE_AREARISERVATA_CIPE\";i:96;s:36:\"ROLE_DELETE_AREARISERVATA_CIPE_CHECK\";i:97;s:25:\"ROLE_DELETE_FIRMATARITIPO\";i:98;s:23:\"ROLE_EDIT_FIRMATARITIPO\";i:99;s:34:\"ROLE_EDIT_AREARISERVATA_CIPE_CHECK\";i:100;s:28:\"ROLE_EDIT_AREARISERVATA_CIPE\";i:101;s:30:\"ROLE_CREATE_AREARISERVATA_CIPE\";i:102;s:36:\"ROLE_CREATE_AREARISERVATA_CIPE_CHECK\";i:103;s:25:\"ROLE_CREATE_FIRMATARITIPO\";i:104;s:17:\"ROLE_READ_MONITOR\";i:105;s:19:\"ROLE_CREATE_MONITOR\";i:106;s:17:\"ROLE_EDIT_MONITOR\";i:107;s:19:\"ROLE_DELETE_MONITOR\";i:108;s:23:\"ROLE_READ_MONITOR_GROUP\";i:109;s:25:\"ROLE_CREATE_MONITOR_GROUP\";i:110;s:23:\"ROLE_EDIT_MONITOR_GROUP\";i:111;s:25:\"ROLE_DELETE_MONITOR_GROUP\";i:112;s:27:\"ROLE_DELETE_AMMINISTRAZIONI\";i:113;s:20:\"ROLE_DELETE_DELIBERE\";i:114;s:18:\"ROLE_READ_REGISTRI\";i:115;s:20:\"ROLE_DELETE_REGISTRI\";i:116;s:20:\"ROLE_CREATE_REGISTRI\";i:117;s:18:\"ROLE_EDIT_REGISTRI\";i:118;s:17:\"ROLE_EDIT_CIPEODG\";i:119;s:20:\"ROLE_EDIT_PRECIPEODG\";i:120;s:28:\"ROLE_READ_ADEMPIMENTI_AMBITI\";i:121;s:30:\"ROLE_CREATE_ADEMPIMENTI_AMBITI\";i:122;s:28:\"ROLE_EDIT_ADEMPIMENTI_AMBITI\";i:123;s:30:\"ROLE_DELETE_ADEMPIMENTI_AMBITI\";i:124;s:28:\"ROLE_READ_ADEMPIMENTI_AZIONI\";i:125;s:30:\"ROLE_CREATE_ADEMPIMENTI_AZIONI\";i:126;s:28:\"ROLE_EDIT_ADEMPIMENTI_AZIONI\";i:127;s:30:\"ROLE_DELETE_ADEMPIMENTI_AZIONI\";i:128;s:30:\"ROLE_READ_ADEMPIMENTI_SOGGETTI\";i:129;s:31:\"ROLE_READ_ADEMPIMENTI_TIPOLOGIE\";i:130;s:32:\"ROLE_CREATE_ADEMPIMENTI_SOGGETTI\";i:131;s:33:\"ROLE_CREATE_ADEMPIMENTI_TIPOLOGIE\";i:132;s:31:\"ROLE_EDIT_ADEMPIMENTI_TIPOLOGIE\";i:133;s:30:\"ROLE_EDIT_ADEMPIMENTI_SOGGETTI\";i:134;s:32:\"ROLE_DELETE_ADEMPIMENTI_SOGGETTI\";i:135;s:33:\"ROLE_DELETE_ADEMPIMENTI_TIPOLOGIE\";}'),
(2, '2', 'Solo lettura delibere', 'a:3:{i:0;s:18:\"ROLE_READ_DELIBERE\";i:1;s:19:\"ROLE_READ_FIRMATARI\";i:2;s:14:\"ROLE_READ_TAGS\";}'),
(3, '3', 'Lettura di tutti i contenuti + scrittura adempimenti', 'a:50:{i:0;s:25:\"ROLE_READ_AMMINISTRAZIONI\";i:1;s:18:\"ROLE_READ_MITTENTI\";i:2;s:18:\"ROLE_READ_TITOLARI\";i:3;s:19:\"ROLE_READ_FASCICOLI\";i:4;s:18:\"ROLE_READ_REGISTRI\";i:5;s:16:\"ROLE_READ_GROUPS\";i:6;s:16:\"ROLE_READ_UFFICI\";i:7;s:20:\"ROLE_READ_RUOLI_CIPE\";i:8;s:14:\"ROLE_READ_TAGS\";i:9;s:17:\"ROLE_READ_PRECIPE\";i:10;s:23:\"ROLE_READ_MONITOR_GROUP\";i:11;s:17:\"ROLE_READ_MONITOR\";i:12;s:21:\"ROLE_READ_ADEMPIMENTI\";i:13;s:18:\"ROLE_READ_DELIBERE\";i:14;s:27:\"ROLE_READ_CIPEARGOMENTITIPO\";i:15;s:23:\"ROLE_READ_CIPEESITITIPO\";i:16;s:19:\"ROLE_READ_CIPEESITI\";i:17;s:23:\"ROLE_READ_FIRMATARITIPO\";i:18;s:14:\"ROLE_READ_CIPE\";i:19;s:19:\"ROLE_READ_FIRMATARI\";i:20;s:20:\"ROLE_READ_PRECIPEODG\";i:21;s:17:\"ROLE_READ_CIPEODG\";i:22;s:31:\"ROLE_READ_AREARISERVATA_PRECIPE\";i:23;s:37:\"ROLE_READ_AREARISERVATA_PRECIPE_CHECK\";i:24;s:28:\"ROLE_READ_AREARISERVATA_CIPE\";i:25;s:34:\"ROLE_READ_AREARISERVATA_CIPE_CHECK\";i:26;s:15:\"ROLE_READ_USERS\";i:27;s:30:\"ROLE_READ_ADEMPIMENTI_SCADENZE\";i:28;s:31:\"ROLE_READ_ADEMPIMENTI_TIPOLOGIE\";i:29;s:30:\"ROLE_READ_ADEMPIMENTI_SOGGETTI\";i:30;s:28:\"ROLE_READ_ADEMPIMENTI_AZIONI\";i:31;s:28:\"ROLE_READ_ADEMPIMENTI_AMBITI\";i:32;s:23:\"ROLE_CREATE_ADEMPIMENTI\";i:33;s:21:\"ROLE_EDIT_ADEMPIMENTI\";i:34;s:23:\"ROLE_DELETE_ADEMPIMENTI\";i:35;s:32:\"ROLE_CREATE_ADEMPIMENTI_SCADENZE\";i:36;s:30:\"ROLE_EDIT_ADEMPIMENTI_SCADENZE\";i:37;s:32:\"ROLE_DELETE_ADEMPIMENTI_SCADENZE\";i:38;s:33:\"ROLE_DELETE_ADEMPIMENTI_TIPOLOGIE\";i:39;s:32:\"ROLE_DELETE_ADEMPIMENTI_SOGGETTI\";i:40;s:30:\"ROLE_DELETE_ADEMPIMENTI_AZIONI\";i:41;s:30:\"ROLE_DELETE_ADEMPIMENTI_AMBITI\";i:42;s:31:\"ROLE_EDIT_ADEMPIMENTI_TIPOLOGIE\";i:43;s:30:\"ROLE_EDIT_ADEMPIMENTI_SOGGETTI\";i:44;s:28:\"ROLE_EDIT_ADEMPIMENTI_AZIONI\";i:45;s:28:\"ROLE_EDIT_ADEMPIMENTI_AMBITI\";i:46;s:33:\"ROLE_CREATE_ADEMPIMENTI_TIPOLOGIE\";i:47;s:32:\"ROLE_CREATE_ADEMPIMENTI_SOGGETTI\";i:48;s:30:\"ROLE_CREATE_ADEMPIMENTI_AZIONI\";i:49;s:30:\"ROLE_CREATE_ADEMPIMENTI_AMBITI\";}');";


    $resGroup = mysqli_query($db, $queryGroup);
    if (!$resGroup) { return mysqli_error($db); }

    $saltADMIN = uniqid(mt_rand());
    $passwordADMIN = password_hash('Tick-Tack-Tock!1000$"', PASSWORD_DEFAULT);
    $login = "mosic-admin@governo.it";
    $queryUser = "INSERT INTO `fos_user` (`id`, `username`, `email`, `enabled`, `salt`, `password`, `roles`, `username_canonical`, `email_canonical`, `firstName`, `lastName`, `created`, `id_uffici`, `cessato_servizio`, `ip`, `stazione`, `id_ruoli_cipe`) VALUES
(1,  '".$login."', '".$login."', 1, '".$saltADMIN."',  '".$passwordADMIN."', 'a:0:{}', '".$login."', '".$login."', 'Mosic', 'Admin', '2017-01-01 00:00:00', 1, '0', '0.0.0.0', 'nd', 2);";
    $resUser = mysqli_query($db, $queryUser);
    if (!$resUser) { return mysqli_error($db); }

    $queryUserGroup = "INSERT INTO `fos_user_group` (`user_id`, `group_id`) VALUES (1, 1);";
    $resUserGroup = mysqli_query($db, $queryUserGroup);
    if (!$resUserGroup) { return mysqli_error($db); }


    //popolo la tabella
    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {
            $enabled = 1;
            $cessatoServizio = 0;
            if ($row['CessatoServizio'] == 'TRUE') {
                $enabled = 0;
                $cessatoServizio = 1;
            }
            
            $salt = "";
            $password = "";
            $roles = "a:0:{}";

            $idRuolo = 0;
            $query4 = 'SELECT * FROM msc_ruoli_cipe WHERE codice = "'.$row['Codice_Livello'].'"';
            $res4 = mysqli_query($db, $query4);
            if (!$res4) {
                return mysqli_error($db);
            }
            //echo $query4 . "<br>";
            if (mysqli_num_rows($res4) >= 1) {
                while ($row4 = mysqli_fetch_array($res4)) {
                    $idRuolo = $row4['id'];
                }
            }

            $salt = uniqid(mt_rand());
            $password = password_hash($row['Password'], PASSWORD_DEFAULT);
            
            $query = 'INSERT IGNORE INTO `fos_user`(
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
                                                "' . $cessatoServizio . '",
                                                "' . $row['Ip'] . '",
                                                "' . $row['Stazione'] . '",
                                                "' . $idRuolo . '"
                                                )';
            //echo $query . "<br>";
            $res2 = mysqli_query($db, $query);
            if (!$res2) {
                return mysqli_error($db);
            }
            
            
            $idGruppo = 3;
//            $query1 = 'SELECT * FROM fos_group WHERE codice = "'.$row['Codice_Livello'].'"';
//            $res1 = mysqli_query($db, $query1);
//            if (!$res1) {
//                return mysqli_error($db);
//            }
//            //echo $query1 . "<br>";
//            if (mysqli_num_rows($res1) >= 1) {
//                while ($row1 = mysqli_fetch_array($res1)) {
//                    $idGruppo = $row1['id'];
//                }
//            }

          if ($row['Userid'] != "SBANFI") {
              $query3 = 'INSERT INTO `fos_user_group`(`user_id`,`group_id`) VALUES ("' . $row['chiave'] . '","' . $idGruppo . '")';
              $res3 = mysqli_query($db, $query3);
              if (!$res3) {
                  return mysqli_error($db);
              }
          } else {
              $querySBANFI_insert = "INSERT INTO `fos_user_group` (`user_id`, `group_id`) VALUES (".$row['chiave'].", 1);";
              $resSBANFI_insert = mysqli_query($db, $querySBANFI_insert);

          }
        }
    }

    return;
}



function setRelAmministrazioniFascicoli() {
    global $db;

    $query = "SELECT id, id_amministrazione FROM msc_fascicoli";
    $res = mysqli_query($db, $query);
    if (!$res) {
        return mysqli_error($db);
    }

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
            $res2 = mysqli_query($db, $query);
            if (!$res2) {
                return mysqli_error($db);
            }
        }
    }

    return;
}

function setRelAmministrazioniRegistri() {
    global $db;
    $query = "SELECT id, id_amministrazione FROM msc_registri";
    $res = mysqli_query($db, $query);
    if (!$res) {
        return mysqli_error($db);
    }
    //svuoto la tabella
    $query = 'TRUNCATE msc_rel_amministrazioni_registri';
    mysqli_query($db, $query);
    
    //popolo la tabella
    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {
            $query = 'INSERT INTO `msc_rel_amministrazioni_registri`(
                                                 `id_registri`,
                                                 `id_amministrazioni`
                                                )
                                                VALUES (
                                                "' . $row['id'] . '",
                                                "' . $row['id_amministrazione'] . '"
                                                )';
            //echo $query . "<br>";
            $res2 = mysqli_query($db, $query);
            if (!$res2) {
                return mysqli_error($db);
            }
        }
    }
    return;
}


function createRegistri() {
    global $db;
    
    $query = "SELECT * FROM Registro ORDER BY Codice_Registro";
    $res = mysqli_query($db, $query);
    if (!$res) {
        return mysqli_error($db);
    }

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
            if (!$res1) {
                return mysqli_error($db);
            }
            $descrizioneSottofascicolo = "";
            while ($row1 = mysqli_fetch_array($res1)) {
                $descrizioneSottofascicolo = $row1['Descrizione_SottoFascicolo'];
            }
            
            //GET id_titolari
            $query2 = "SELECT id FROM msc_titolari WHERE codice = '" .$row['Codice_Titolario']. "'";
            //echo $query2 . "<br>";
            $res2 = mysqli_query($db, $query2);
            if (!$res2) {
                return mysqli_error($db);
            }
            $idTitolario = "";
            while ($row2 = mysqli_fetch_array($res2)) {
                $idTitolario = $row2['id'];
            }

            $MittenteRegistro = str_replace('"','',$row['Mittente_Registro']);

            //GET id_mittenti
            $query3 = 'SELECT id FROM msc_mittenti WHERE denominazione = "' .$MittenteRegistro. '"';
            //echo $query3 . "<br>";
            $res3 = mysqli_query($db, $query3);
            if (!$res3) {
                return mysqli_error($db) . " aaa";
            }
            $denominazioneMittente = "";
            while ($row3 = mysqli_fetch_array($res3)) {
                $denominazioneMittente = $row3['id'];
            }


            $oggettoRegistro = str_replace('"','',$row['Oggetto_Registro']);
            $descrizioneSottofascicolo = str_replace('"','',$descrizioneSottofascicolo);
            $AnnotazioniRegistro = str_replace('"','',$row['Annotazioni_Registro']);
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
                                                  "' . $oggettoRegistro . '",
                                                  "' . $row['Codice_Amministrazione'] . '",
                                                  "' . $MittenteRegistro . '",
                                                  "' . $row['Codice_Titolario'] . '",
                                                  "' . $row['Numero_Fascicolo'] . '",
                                                  "' . $row['Numero_SottoFascicolo'] . '",
                                                  "' . $row['Proposta_Cipe'] . '",
                                                  "' . $AnnotazioniRegistro . '",
                                                  "' . 0 . '",
                                                  "' . $denominazioneMittente . '",
                                                  "' . $idTitolario . '",
                                                  "' . $descrizioneSottofascicolo . '"
                                                )';

            //echo $query . "<br><br>";
            $res4 = mysqli_query($db, $query);
            if (!$res4) {
                return mysqli_error($db) . "at LINE " . $row['Codice_Registro'];
            }


        }
    }

    return;
}


function createEsitiCipe() {
    global $db;

    $query = "SELECT * FROM EsitiCipe";
    $res = mysqli_query($db, $query);
    if (!$res) {
        return mysqli_error($db);
    }

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
                $res2 = mysqli_query($db, $query);
                if (!$res2) {
                    return mysqli_error($db);
                }
            }
        }
    }

    return;
}

function createFascicoli() {
    global $db;
    
    $query = "SELECT * FROM Repertorio";
    $res = mysqli_query($db, $query);
    if (!$res) {
        return mysqli_error($db);
    }
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
            if (!$res1) {
                return mysqli_error($db);
            }
            $idEsitoCipe = "";
            while ($row1 = mysqli_fetch_array($res1)) {
                $idEsitoCipe = $row1['id'];
            }                   
            //GET id_titolari
            $query2 = "SELECT id FROM msc_titolari WHERE codice = '" .$row['Codice_Titolario']. "'";
            //echo $query2 . "<br>";
            $res2 = mysqli_query($db, $query2);
            if (!$res2) {
                return mysqli_error($db);
            }
            $idTitolario = "";
            while ($row2 = mysqli_fetch_array($res2)) {
                $idTitolario = $row2['id'];
            }

            $DataMagazzino = explode("/",$row['Data_Magazzino']);
            $DataMagazzino = $DataMagazzino[2] . "-" . $DataMagazzino[1] . "-" . $DataMagazzino[0];
            $DataCipe = explode("/",$row['Data_Cipe']);
            $DataCipe = $DataCipe[2] . "-" . $DataCipe[1] . "-" . $DataCipe[0];
            $DataCipe2 = explode("/",$row['Data_Cipe2']);
            $DataCipe2 = $DataCipe2[2] . "-" . $DataCipe2[1] . "-" . $DataCipe2[0];


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
                                                  "' . $DataMagazzino . '",
                                                  "' . $DataCipe . '",
                                                  "' . $DataCipe2 . '",
                                                  "' . $row['Archiviazione_Repertorio'] . '",
                                                  "' . str_replace('"','',$row['Annotazioni_Repertorio']) . '",

                                                  "' . $row['Numero_Delibera'] . '",
                                                  "' . $idEsitoCipe . '",
                                                  "' . 0 . '",
                                                  "' . $idTitolario . '"
                                                )';

            //echo $query . "<br><br>";
            $res3 = mysqli_query($db, $query);
            if (!$res3) {
                return mysqli_error($db);
            }
        }
    }

    return;
}

function setIdFascicoliRegistri() {
    global $db;

    
    
    $query = "SELECT r.*, r.id as idR, f.id as fascicolo_id FROM `msc_registri` as r
              LEFT JOIN msc_fascicoli as f ON r.numero_fascicolo = f.numero_fascicolo AND r.codice_titolario = f.codice_titolario";
    $res = mysqli_query($db, $query);
    if (!$res) {
        return mysqli_error($db);
    }

    //echo $query . "<br>";

    //aggiorno tabella
    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {
            $query = 'UPDATE `msc_registri` SET `id_fascicoli`= "' . $row['fascicolo_id'] . '"  WHERE id = "' . $row['idR'] . '"';
            //echo $query . "<br>";
            $res2 = mysqli_query($db, $query);
            if (!$res2) {
                return mysqli_error($db);
            }
        }
    }
}







function setMittentiRegistri()
{
    global $db;

    $query = "SELECT * FROM msc_mittente as m";
    $res = mysqli_query($db, $query);
    if (!$res) {
        return mysqli_error($db);
    }

    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {

            $query2 = 'UPDATE `msc_registri` SET `id_mittente`= "' . $row['id'] . '"  WHERE mittente = "' . $row['denominazione'] . '"';
            //echo $query2 . "<br>";
            $res2 = mysqli_query($db, $query2);
            if (!$res2) {
                return mysqli_error($db);
            }
        }
    }

    return;
}


function setOggettoRegistri()
{
    global $db;

    $query = "SELECT * FROM msc_registri as r
							LEFT JOIN TABLE16 as t ON r.id = t.id3
							WHERE r.id >= 7000 AND r.id <= 8000";
    $res = mysqli_query($db, $query);
    if (!$res) {
        return mysqli_error($db);
    }

    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {
            //mb_check_encoding($row['COL10'], 'UTF-8')
            //echo $row['mittente2'] . "<br>";

            $query2 = 'UPDATE `msc_registri` SET `oggetto`= "' . $row['oggetto3'] . '"  WHERE id = ' . $row['id'];

            //echo $query2 . "<br>";
            $res2 = mysqli_query($db, $query2);
            if (!$res2) {
                return mysqli_error($db);
            }

        }
    }

    return;
}


function updateIdTitolari()
{
    global $db;

    $query = "SELECT * , t.id as idT, r.id as idR FROM msc_fascicoli as r
		LEFT JOIN msc_titolari as t ON r.codice_titolario = t.codice";
    $res = mysqli_query($db, $query);
    if (!$res) {
        return mysqli_error($db);
    }

    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {
            //mb_check_encoding($row['COL10'], 'UTF-8')
            //echo $row['mittente2'] . "<br>";

            $query2 = 'UPDATE `msc_fascicoli` SET `id_titolari`= "' . $row['idT'] . '"  WHERE id = ' . $row['idR'];

            //echo $query2 . "<br>";
            $res2 = mysqli_query($db, $query2);
            if (!$res2) {
                return mysqli_error($db);
            }

        }
    }

    return;
}


function gestioneFilesRegistri($filePath, $path)
{
    global $db;
    //$path = "../files/REGISTRO_MOSIC";
    //$results = scandir($path);

    //svuoto la tabella
    $query = 'TRUNCATE msc_allegati';
    mysqli_query($db, $query);

    //svuoto la tabella
    $query = 'TRUNCATE msc_rel_allegati_registri';
    mysqli_query($db, $query);

    $debug = array();

    //$path = realpath('yourfolder/examplefolder');
    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path)) as $filename) {
        if (!is_dir($filename)) {
            $filename = str_replace("../", "", $filename);
            $exploda_path = explode("/", $filename);

            if (!is_dir("../".$exploda_path[0] . "/" . $exploda_path[1]. "/" . $exploda_path[2]. "/" . $exploda_path[3]. "/" . $exploda_path[4])) { //se NON sono un sottofascicolo
                $exploda_nome = explode("-", $exploda_path[4]);  //$exploda_path[4] = nome file
                //echo $exploda_nome[0] . "<br>";
                //echo $filename . " ---> ".$exploda_nome[0]."<br>";
            } else { //altrimenti sono un sottofascicolo
                $exploda_nome = explode("-", $exploda_path[5]);  //$exploda_path[4] = nome file
                //echo $filename . " ---> ".$exploda_nome[0]."<br>";
            }

            if (is_numeric($exploda_nome[0])) {
                //inserisco nella tabella allegati Data e $item
                $query2 = 'INSERT INTO `msc_allegati`(`data`, `file`) VALUES (NOW(),"' . $filePath . $filename . '")';
                $res2 = mysqli_query($db, $query2);

                //echo $query2 . "<br>";

                if (!$res2) {
                    return mysqli_error($db) . " res2";
                }

                $last_id = mysqli_insert_id($db); //ultimo id inserito

                $query3 = "INSERT INTO `msc_rel_allegati_registri`(`id_registri`, `id_allegati`) VALUES ($exploda_nome[0], $last_id)";
                $res3 = mysqli_query($db, $query3);

                //echo $query3 . "<br>";

                if (!$res3) {
                    return mysqli_error($db) . " res3";
                }
            } else {
                if ($exploda_nome[0] != "Thumbs.db") {
                    $debug[] = "ERRORE: " . $filename . " ---> " . $exploda_nome[0] . "<br>";
                    //echo "ERRORE: " . $filename . " ---> " . $exploda_nome[0] . "<br>";
                }
            }



        }
    }

    if (count($debug) > 0) {
        return $debug;
    } else {
        return;
    }
}



function setSottofascicoliDenominazioneSuRegistri() {
    global $db;

    $query = "SELECT * FROM msc_sottofascicoli";
    $res = mysqli_query($db, $query);
    if (!$res) {
        return mysqli_error($db);
    }

    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {
            //echo $row['Descrizione_SottoFascicolo'] . "<br>";
            $query2 = 'UPDATE `msc_registri` SET `denominazione_sottofascicolo`= "' . $row['Descrizione_SottoFascicolo'] . '"  WHERE numero_sottofascicolo = ' . $row['Numero_SottoFascicolo'];
            $res2 = mysqli_query($db, $query2);
            if (!$res2) {
                return mysqli_error($db);
            }
        }
    }

    return;
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
                //echo $exploda_path[4] . " ----------> $exploda_path[3] ---------> $exploda_path[2] <br>"; //nome file  ----> tipo -----> data precipe
                //echo $filename . "<br>";

                //inserisco nella tabella allegati Data e $item
                $query2 = "INSERT INTO `msc_allegati`(`data`, `file`) VALUES (NOW(),'" . $filePath . $filename . "')";
                $res2 = mysqli_query($db, $query2);
                if (!$res2) {
                    return mysqli_error($db);
                }
                $last_id = mysqli_insert_id($db); //ultimo id inserito

                $query = "SELECT * FROM msc_precipe as p WHERE data = '" . $exploda_path[2] . "'";
                $res = mysqli_query($db, $query);
                if (!$res) {
                    return mysqli_error($db);
                }
                $id_precipe = "";
                if (mysqli_num_rows($res) >= 1) {
                    while ($row = mysqli_fetch_array($res)) {
                        $id_precipe = $row['id'];
                        //echo "trovato --> ". $row['id'];

                        if ($exploda_path[3] == "APG" || $exploda_path[3] == "OSS" || $exploda_path[3] == "TLX") {
                            $query3 = "INSERT INTO `msc_rel_allegati_precipe`(`id_precipe`, `id_allegati`, `tipo`) VALUES ($id_precipe, $last_id, '$exploda_path[3]')";
                            $res3 = mysqli_query($db, $query3);
                            if (!$res3) {
                                return mysqli_error($db);
                            }
                            //echo $query3 . "<br>";
                        }
                    }
                }

            }

            //echo "-<br>";


        }
    }

    return;
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
                //echo $exploda_path[4] . " ----------> $exploda_path[3] ---------> $exploda_path[2] <br>"; //nome file  ----> tipo -----> data precipe
                //echo $filename . "<br>";

                //inserisco nella tabella allegati Data e $item
                $query2 = "INSERT INTO `msc_allegati`(`data`, `file`) VALUES (NOW(),'" . $filePath . $filename . "')";
                $res2 = mysqli_query($db, $query2);
                if (!$res2) {
                    return mysqli_error($db);
                }
                $last_id = mysqli_insert_id($db); //ultimo id inserito

                $query = "SELECT * FROM msc_cipe as p WHERE data = '" . $exploda_path[2] . "'";
                $res = mysqli_query($db, $query);
                if (!$res) {
                    return mysqli_error($db);
                }
                $id_cipe = "";
                if (mysqli_num_rows($res) >= 1) {
                    while ($row = mysqli_fetch_array($res)) {
                        $id_cipe = $row['id'];
                        //echo "trovato --> ". $row['id'];

                        if ($exploda_path[3] == "APG" || $exploda_path[3] == "OSS" || $exploda_path[3] == "TLX" || $exploda_path[3] == "EST") {
                            $query3 = "INSERT INTO `msc_rel_allegati_cipe`(`id_cipe`, `id_allegati`, `tipo`) VALUES ($id_cipe, $last_id, '$exploda_path[3]')";
                            $res3 = mysqli_query($db, $query3);
                            if (!$res3) {
                                return mysqli_error($db);
                            }
                            //echo $query3 . "<br>";
                        }
                    }
                }

            }

            //echo "-<br>";


        }
    }

    return;
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
    if (!$res) {
        return mysqli_error($db);
    }
    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {

            $query2 = 'UPDATE `msc_registri` SET `id_fascicoli`= "' . $row['idF'] . '"  WHERE id = ' . $row['idR'];

            //echo $query2 . "<br>";
            $res2 = mysqli_query($db, $query2);
            if (!$res2) {
                return mysqli_error($db);
            }
        }
    }

    return;
}


function raggruppaPreCipe()
{
    global $db;
    
    //svuoto la tabella
    $query = 'TRUNCATE msc_precipe';
    mysqli_query($db, $query);


    $query = "SELECT Data_PreCipe FROM PreCipe GROUP BY Data_PreCipe";
    $res = mysqli_query($db, $query);
    if (!$res) {
        return mysqli_error($db);
    }

    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {
            $query2 = 'INSERT INTO msc_precipe SET data = "' . $row['Data_PreCipe'] . '"';

            //echo $query2 . "<br>";
            $res2 = mysqli_query($db, $query2);
            if (!$res2) {
                return mysqli_error($db);
            }
        }
    }

    return;
}


function setOrdiniPreCipe()
{ //temporaneamente deve esistere la data anche nella tabella ordini!
    global $db;

    $query = "SELECT * FROM msc_precipe";
    $res = mysqli_query($db, $query);
    if (!$res) {
        return mysqli_error($db);
    }

    //svuoto la tabella
    $query = 'TRUNCATE msc_precipe_ordini';
    mysqli_query($db, $query);

    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {
            $query2 = 'SELECT * FROM PreCipe WHERE Data_PreCipe = "' . $row['data'] . '"';
            $res2 = mysqli_query($db, $query2);
            if (!$res2) {
                return mysqli_error($db);
            }

            if (mysqli_num_rows($res2) >= 1) {
                while ($row2 = mysqli_fetch_array($res2)) {
                    $argomenti = "";
                    $query_argomenti = 'SELECT * FROM msc_argomenti WHERE codice = "' . $row2['Codice_ArgomentoPreCipe'] . '"';
                    $res_argomenti = mysqli_query($db, $query_argomenti);
                    if (!$res_argomenti) {
                        return mysqli_error($db);
                    }
                    while ($row_argomenti = mysqli_fetch_array($res_argomenti)) {
                        $argomenti = $row_argomenti['id'];
                    }

                    $titolari = "";
                    $query_titolari = 'SELECT * FROM msc_titolari WHERE codice = "' . $row2['Codice_Titolario'] . '"';
                    $res_titolari = mysqli_query($db, $query_titolari);
                    if (!$res_titolari) {
                        return mysqli_error($db);
                    }
                    while ($row_titolari = mysqli_fetch_array($res_titolari)) {
                        $titolari = $row_titolari['id'];
                    }

                    $fascicoli = "";
                    $query_fascicoli = 'SELECT * FROM msc_fascicoli WHERE codice_titolario = "' . $row2['Codice_Titolario'] . '" AND numero_fascicolo = "' . $row2['Numero_Fascicolo'] . '"';
                    $res_fascicoli = mysqli_query($db, $query_fascicoli);
                    if (!$res_fascicoli) {
                        return mysqli_error($db);
                    }
                    while ($row_fascicoli = mysqli_fetch_array($res_fascicoli)) {
                        $fascicoli = $row_fascicoli['id'];
                    }

                    $uffici = "";
                    $query_uffici = 'SELECT * FROM msc_uffici WHERE codice = "' . $row2['Codice_Ufficio'] . '"';
                    $res_uffici = mysqli_query($db, $query_uffici);
                    if (!$res_uffici) {
                        return mysqli_error($db);
                    }
                    while ($row_uffici = mysqli_fetch_array($res_uffici)) {
                        $uffici = $row_uffici['id'];
                    }

                    $risultanze = "";
                    $query_risultanze = 'SELECT * FROM msc_risultanze WHERE codice = "' . $row2['Codice_RisultanzaPreCipe'] . '"';
                    $res_risultanze = mysqli_query($db, $query_risultanze);
                    if (!$res_risultanze) {
                        return mysqli_error($db);
                    }
                    while ($row_risultanze = mysqli_fetch_array($res_risultanze)) {
                        $risultanze = $row_risultanze['id'];
                    }

                    $OggettoPreCipe = str_replace('"','',$row2['Oggetto_PreCipe']);
                    $query3 = 'INSERT INTO msc_precipe_ordini SET 
                        id = "' . $row2['Codice_PreCipe'] . '", 
                        id_precipe = "' . $row['id'] . '", 
                        progressivo = "' . $row2['Progressivo_PreCipe'] . '", 
                        id_titolari = "' . $titolari . '", 
                        id_fascicoli = "' . $fascicoli . '", 
                        id_argomenti = "' . $argomenti . '", 
                        id_uffici = "' . $uffici . '", 
                        ordine = "' . $row2['Numero_OdgPreCipe'] . '", 
                        denominazione = "' . $OggettoPreCipe . '", 
                        risultanza = "' . $risultanze . '", 
                        annotazioni = "' . $row2['Annotazioni_PreCipe'] . '"                        
                      ';

                    //echo $query3 . "<br>";
                    $res3 = mysqli_query($db, $query3);
                    if (!$res3) {
                        return mysqli_error($db) . " (insert risultanze)";
                    }
                }
            }
        }
    }

    return;
}

function setRegistriPrecipe()
{
    global $db;

    $query = "SELECT * 
              FROM PreCipe";
    $res = mysqli_query($db, $query);
    if (!$res) {
        return mysqli_error($db);
    }

    //svuoto la tabella
    $query = 'TRUNCATE msc_rel_registri_odg';
    mysqli_query($db, $query);

    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {

            if ($row['Codice_Registro'] != "" && $row['Codice_Registro'] != 0 && $row['Codice_Registro'] != null) {
                $query2 = 'INSERT INTO msc_rel_registri_odg SET id_odg = "' . $row['Codice_PreCipe'] . '", id_registri = "' . $row['Codice_Registro'] . '" ';
                //echo $query2 . "<br>";
                $res2 = mysqli_query($db, $query2);
                if (!$res2) {
                    return mysqli_error($db);
                }
            }


            if ($row['Codice_Registro2'] != "" && $row['Codice_Registro2'] != 0 && $row['Codice_Registro2'] != null) {
                $query2 = 'INSERT INTO msc_rel_registri_odg SET id_odg = "' . $row['Codice_PreCipe'] . '", id_registri = "' . $row['Codice_Registro2'] . '" ';
                //echo $query2 . "<br>";
                $res2 = mysqli_query($db, $query2);
                if (!$res2) {
                    return mysqli_error($db);
                }
            }

            if ($row['Codice_Registro3'] != "" && $row['Codice_Registro3'] != 0 && $row['Codice_Registro3'] != null) {
                $query2 = 'INSERT INTO msc_rel_registri_odg SET id_odg = "' . $row['Codice_PreCipe'] . '", id_registri = "' . $row['Codice_Registro3'] . '" ';
                //echo $query2 . "<br>";
                $res2 = mysqli_query($db, $query2);
                if (!$res2) {
                    return mysqli_error($db);
                }
            }

        }
    }

    return;
}


function raggruppaCipe() {
    global $db;

    $query = "SELECT * FROM Cipe as c
              LEFT JOIN Sedute_Cipe as s ON c.Data_Cipe = s.Data_SedutaCipe
              GROUP BY c.Data_Cipe";
    $res = mysqli_query($db, $query);
    if (!$res) {
        return mysqli_error($db);
    }

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
            if (!$res2) {
                return mysqli_error($db);
            }
        }
    }

    return;
}



function setOrdiniCipe()
{ //temporaneamente deve esistere la data anche nella tabella ordini!
    global $db;

    $query = "SELECT * FROM msc_cipe";
    $res = mysqli_query($db, $query);
    if (!$res) {
        return mysqli_error($db);
    }

    //svuoto la tabella
    $query = 'TRUNCATE msc_cipe_ordini';
    mysqli_query($db, $query);

    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {
            $query2 = 'SELECT * FROM Cipe WHERE Data_Cipe = "' . $row['data'] . '"';
            $res2 = mysqli_query($db, $query2);
            if (!$res2) {
                return mysqli_error($db);
            }

            if (mysqli_num_rows($res2) >= 1) {
                while ($row2 = mysqli_fetch_array($res2)) {
                    $argomenti = "";
                    $query_argomenti = 'SELECT * FROM msc_argomenti WHERE codice = "' . $row2['Codice_ArgomentoCipe'] . '"';
                    $res_argomenti = mysqli_query($db, $query_argomenti);
                    if (!$res_argomenti) {
                        return mysqli_error($db);
                    }
                    while ($row_argomenti = mysqli_fetch_array($res_argomenti)) {
                        $argomenti = $row_argomenti['id'];
                    }

                    $titolari = "";
                    $query_titolari = 'SELECT * FROM msc_titolari WHERE codice = "' . $row2['Codice_Titolario'] . '"';
                    $res_titolari = mysqli_query($db, $query_titolari);
                    if (!$res_titolari) {
                        return mysqli_error($db);
                    }
                    while ($row_titolari = mysqli_fetch_array($res_titolari)) {
                        $titolari = $row_titolari['id'];
                    }

                    $fascicoli = "";
                    $query_fascicoli = 'SELECT * FROM msc_fascicoli WHERE codice_titolario = "' . $row2['Codice_Titolario'] . '" AND numero_fascicolo = "' . $row2['Numero_Fascicolo'] . '"';
                    $res_fascicoli = mysqli_query($db, $query_fascicoli);
                    if (!$res_fascicoli) {
                        return mysqli_error($db);
                    }
                    while ($row_fascicoli = mysqli_fetch_array($res_fascicoli)) {
                        $fascicoli = $row_fascicoli['id'];
                    }

                    $uffici = "";
                    $query_uffici = 'SELECT * FROM msc_uffici WHERE codice = "' . $row2['Codice_Ufficio'] . '"';
                    $res_uffici = mysqli_query($db, $query_uffici);
                    if (!$res_uffici) {
                        return mysqli_error($db);
                    }
                    while ($row_uffici = mysqli_fetch_array($res_uffici)) {
                        $uffici = $row_uffici['id'];
                    }

                    $risultanze = "";
                    $query_risultanze = 'SELECT * FROM msc_risultanze WHERE codice = "' . $row2['Codice_RisultanzaCipe'] . '"';
                    $res_risultanze = mysqli_query($db, $query_risultanze);
                    if (!$res_risultanze) {
                        return mysqli_error($db);
                    }
                    while ($row_risultanze = mysqli_fetch_array($res_risultanze)) {
                        $risultanze = $row_risultanze['id'];
                    }

//                    $delibere = "";
//                    $query_delibere = 'SELECT * FROM msc_delibere WHERE data = "' . $row2['Data_Cipe'] . '" AND numero = "'.$row2['Numero_Delibera'].'"';
//                    $res_delibere = mysqli_query($db, $query_delibere);
//                    if (!$res_delibere) {
//                        return mysqli_error($db) . " (msc_delibere)";
//                    }
//                    //echo $query_delibere . "<br>";
//
//                    while ($row_delibere = mysqli_fetch_array($res_delibere)) {
//                        $delibere = $row_delibere['id'];
//                    }




                    $OggettoCipe = str_replace('"','',$row2['Oggetto_Cipe']);
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
                        denominazione = "' . $OggettoCipe . '", 
                        risultanza = "' . $risultanze . '", 
                        id_esito = "' . $row2['Codice_EsitoCipe'] . '", 
                        tipo_esito = "' . $row2['Codice_TipoEsitoCipe'] . '", 
                        numero_delibera = "' . $row2['Numero_Delibera']  . '", 
                        annotazioni = "' . $row2['Annotazioni_Cipe'] . '"                        
                      ';

                    //echo $query3 . "<br>";
                    $res3 = mysqli_query($db, $query3);
                    if (!$res3) {
                        return mysqli_error($db) . " ( insert msc_cipe_ordini)";
                    }
                }
            }
        }
    }

    return;
}


function setRegistriCipe() {
    global $db;

    $query = "SELECT * FROM Cipe";
    $res = mysqli_query($db, $query);
    if (!$res) {
        return mysqli_error($db);
    }

    //svuoto la tabella
    $query = 'TRUNCATE msc_rel_registri_odg_cipe';
    mysqli_query($db, $query);

    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {

            if ($row['Codice_Registro'] != "" && $row['Codice_Registro'] != 0 && $row['Codice_Registro'] != null) {
                $query2 = 'INSERT INTO msc_rel_registri_odg_cipe SET id_odg_cipe = "' . $row['Codice_Cipe'] . '", id_registri = "' . $row['Codice_Registro'] . '" ';
                //echo $query2 . "<br>";
                $res2 = mysqli_query($db, $query2);
                if (!$res2) {
                    return mysqli_error($db);
                }
            }

            if ($row['Codice_Registro2'] != "" && $row['Codice_Registro2'] != 0 && $row['Codice_Registro2'] != null) {
                $query2 = 'INSERT INTO msc_rel_registri_odg_cipe SET id_odg_cipe = "' . $row['Codice_Cipe'] . '", id_registri = "' . $row['Codice_Registro2'] . '" ';
                //echo $query2 . "<br>";
                $res2 = mysqli_query($db, $query2);
                if (!$res2) {
                    return mysqli_error($db);
                }
            }

            if ($row['Codice_Registro3'] != "" && $row['Codice_Registro3'] != 0 && $row['Codice_Registro3'] != null) {
                $query2 = 'INSERT INTO msc_rel_registri_odg_cipe SET id_odg_cipe = "' . $row['Codice_Cipe'] . '", id_registri = "' . $row['Codice_Registro3'] . '" ';
                //echo $query2 . "<br>";
                $res2 = mysqli_query($db, $query2);
                if (!$res2) {
                    return mysqli_error($db);
                }
            }

        }
    }

    return;
}


function checkRegistriFascicoli()
{
    global $db;

    $query = "SELECT * 
              FROM TABLE33_precipe_full";
    $res = mysqli_query($db, $query);
    if (!$res) {
        return mysqli_error($db);
    }

    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {


        }
    }

    return;
}


function setUfficiPreCipeOdg()
{
    global $db;

    $query = "SELECT * FROM msc_precipe_ordini";
    $res = mysqli_query($db, $query);
    if (!$res) {
        return mysqli_error($db);
    }

    //svuoto la tabella
    $query = 'TRUNCATE msc_rel_uffici_precipe';
    mysqli_query($db, $query);

    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {

            $query2 = 'INSERT INTO msc_rel_uffici_precipe SET id_odg_precipe = "' . $row['id'] . '", id_uffici = "' . $row['id_uffici'] . '" ';
            //echo $query2 . "<br>";
            $res2 = mysqli_query($db, $query2);
            if (!$res2) {
                return mysqli_error($db);
            }

        }
    }

    return;
}

function setUfficiCipeOdg()
{
    global $db;

    $query = "SELECT * FROM msc_cipe_ordini";
    $res = mysqli_query($db, $query);
    if (!$res) {
        return mysqli_error($db);
    }

    //svuoto la tabella
    $query = 'TRUNCATE msc_rel_uffici_cipe';
    mysqli_query($db, $query);

    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {

            $query2 = 'INSERT INTO msc_rel_uffici_cipe SET id_odg_cipe = "' . $row['id'] . '", id_uffici = "' . $row['id_uffici'] . '" ';
            //echo $query2 . "<br>";
            $res2 = mysqli_query($db, $query2);
            if (!$res2) {
                return mysqli_error($db);
            }
        }
    }

    return;
}



function testUpdate($test)
{
    global $db;

    $query2 = 'INSERT INTO ... SET id=20, Data_SedutaCipe = "' . $test.'"';
    $res2 = mysqli_query($db, $query2);
    if (!$res2) {
        return mysqli_error($db);
    }

    return;
}



function aggiornaStato($id, $stato) {
    global $db;

    $query = 'UPDATE `msc_precipe` SET `public_reserved_status`= "' . $stato . '"  WHERE id = "' . $id . '"';
    $res2 = mysqli_query($db, $query);
    if (!$res2) {
        return mysqli_error($db);
    }

    return;
}


function aggiornaURL($id, $stato) {
    global $db;

    $query = 'UPDATE `msc_precipe` SET `public_reserved_URL`= "' . $stato . '"  WHERE id = "' . $id . '"';
    $res2 = mysqli_query($db, $query);
    if (!$res2) {
        return mysqli_error($db);
    }

    return;
}

function aggiornaStatoCipe($id, $stato) {
    global $db;

    $query = 'UPDATE `msc_cipe` SET `public_reserved_status`= "' . $stato . '"  WHERE id = "' . $id . '"';
    $res2 = mysqli_query($db, $query);
    if (!$res2) {
        return mysqli_error($db);
    }

    return;
}


function aggiornaURLCipe($id, $stato) {
    global $db;

    $query = 'UPDATE `msc_cipe` SET `public_reserved_URL`= "' . $stato . '"  WHERE id = "' . $id . '"';
    $res2 = mysqli_query($db, $query);
    if (!$res2) {
        return mysqli_error($db);
    }

    return;
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

            $nuovo_nome = sanitizeFilename($solo_nome) . "." . strtolower($file_info['extension']);

            rename($filename, $file_info['dirname'] . "/" . $nuovo_nome);

            //echo $file_info['dirname']. "<br/>";
            //echo $nuovo_nome . "<br/>";
            //echo $file_info['extension']. "<br/><br/>";

            //svuoto la tabella
            $query = 'TRUNCATE msc_allegati';
            $res = mysqli_query($db, $query);


        }
    }
}

function renameWithNestedMkdir($oldname , $newname)
{
    $targetDir = dirname($newname); // Returns a parent directory's path (operates naively on the input string, and is not aware of the actual filesystem)

    // here $targetDir is "/some/long/nested/path/test2/xxx1"
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true); // third parameter "true" allows the creation of nested directories
    }

    return rename($oldname , $newname);
}


function setDateDelibereGiorni() {
    global $db;

    $query = "SELECT * FROM msc_delibere ORDER BY id DESC";
    $res = mysqli_query($db, $query);
    if (!$res) {
        return mysqli_error($db);
    }

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
            //echo $query2 . "<br>";
            $res2 = mysqli_query($db, $query2);
            if (!$res2) {
                return mysqli_error($db);
            }

            if ($row['id'] == 2589) {
                //echo $row['id'] . " --> " . $row['data_consegna'] . "</br>";
                //echo $GiorniCC . "</br>";
                //echo $query2 . "<br>";
            }

        }
    }

    return;
}

function setUtentiAdempimenti() {
    global $db;

    $query = "SELECT * 
              FROM Adempimenti as a
              LEFT JOIN fos_user as u ON a.Utente_Modifica = u.username";
    $res = mysqli_query($db, $query);
    if (!$res) {
        return mysqli_error($db);
    }

    if (mysqli_num_rows($res) >= 1) {
        while ($row = mysqli_fetch_array($res)) {
            $query2 = 'UPDATE `msc_adempimenti` SET `utente`= "' . $row['id'] . '" WHERE `progressivo` = "' . $row['Progressivo_Adempimento'] . '" AND `codice_scheda` = "' . $row['Codice_Scheda'] .'" AND `id_delibere` = "' . $row['Codice_Delibera'] .'"';

            //echo $query2 . "<br>";
            $res2 = mysqli_query($db, $query2);
            if (!$res2) {
                return mysqli_error($db);
            }

        }
    }

    return;
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
    if (!$res) {
        return mysqli_error($db);
    }

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


//    echo "<pre>";
//    print_r($arrayDelibere);
//    echo "</pre>";

    return;

}






?>