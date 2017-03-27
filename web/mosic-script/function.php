<?php


function setTableRegistri() {
    global $db;
    
    $query = "SELECT * FROM msc_registri as r
							LEFT JOIN TABLE17 as t ON r.id = t.id2
							WHERE r.id >= 7000 AND r.id <=8000";
    $res = mysqli_query($db, $query);
		
    if(mysqli_num_rows($res) >= 1) {
        while($row = mysqli_fetch_array($res)) {
						//mb_check_encoding($row['COL10'], 'UTF-8')
            //echo $row['mittente2'] . "<br>";

				    $query2 = 'UPDATE `msc_registri` SET `mittente`= "'.$row['mittente2'].'", `annotazioni`= "'.$row['annotazioni2'].'"  WHERE id = '.$row['id'];
            
            echo $query2 . "<br>";
            $res2 = mysqli_query($db, $query2);
		
						
        }
    }
       
}


function setMittentiRegistri() {
    global $db;
    
    $query = "SELECT * FROM msc_mittente as m";
    $res = mysqli_query($db, $query);
		
		if(mysqli_num_rows($res) >= 1) {
				while($row = mysqli_fetch_array($res)) {
					
						$query2 = 'UPDATE `msc_registri` SET `id_mittente`= "'.$row['id'].'"  WHERE mittente = "'.$row['denominazione'].'"';
            echo $query2 . "<br>";
            $res2 = mysqli_query($db, $query2);
				}
		}

}


function setOggettoRegistri() {
    global $db;
    
    $query = "SELECT * FROM msc_registri as r
							LEFT JOIN TABLE16 as t ON r.id = t.id3
							WHERE r.id >= 7000 AND r.id <= 8000";
    $res = mysqli_query($db, $query);
		
    if(mysqli_num_rows($res) >= 1) {
        while($row = mysqli_fetch_array($res)) {
						//mb_check_encoding($row['COL10'], 'UTF-8')
            //echo $row['mittente2'] . "<br>";

				    $query2 = 'UPDATE `msc_registri` SET `oggetto`= "'.$row['oggetto3'].'"  WHERE id = '.$row['id'];
            
            echo $query2 . "<br>";
            $res2 = mysqli_query($db, $query2);
		
						
        }
    }
       
}


function updateIdTitolari() {
    global $db;
    
    $query = "SELECT * , t.id as idT, r.id as idR FROM msc_fascicoli as r
		LEFT JOIN msc_titolari as t ON r.codice_titolario = t.codice";
    $res = mysqli_query($db, $query);
		
    if(mysqli_num_rows($res) >= 1) {
        while($row = mysqli_fetch_array($res)) {
						//mb_check_encoding($row['COL10'], 'UTF-8')
            //echo $row['mittente2'] . "<br>";

				    $query2 = 'UPDATE `msc_fascicoli` SET `id_titolari`= "'.$row['idT'].'"  WHERE id = '.$row['idR'];
            
            echo $query2 . "<br>";
            $res2 = mysqli_query($db, $query2);
		
						
        }
    }
       
}


function gestioneFilesRegistri() {
    global $db;
    $path = "../files/REGISTRO_MOSIC";
    //$results = scandir($path);

    //$path = realpath('yourfolder/examplefolder');
    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path)) as $filename) {
            if (!is_dir($filename)) {
                  $filename = str_replace("../","",$filename);
                    $exploda_path = explode("/",$filename);
                    $exploda_nome = explode("-",$exploda_path[4]);  //$exploda_path[4] = nome file
                    echo $exploda_nome[0] . "<br>"; //registro (contenuto nel nome file) 


                    //inserisco nella tabella allegati Data e $item
                    //$query2 = "INSERT INTO `msc_allegati`(`data`, `file`) VALUES (NOW(),'". $filename ."')";
                    //$res2 = mysqli_query($db, $query2);
                    //
                    //$last_id = mysqli_insert_id($db); //ultimo id inserito
                    //
                    //$query3 = "INSERT INTO `msc_rel_allegati_registri`(`id_registri`, `id_allegati`) VALUES ($exploda_nome[0], $last_id)";
                    //$res3 = mysqli_query($db, $query3);
            }
    }						
}

function gestioneFilesPreCipe() {
    global $db;
    $path = "../files/RIUNIONI_PRECIPE";
    //$results = scandir($path);

    //$path = realpath('yourfolder/examplefolder');
    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path)) as $filename) {
        if (!is_dir($filename)) {
            $filename = str_replace("../","",$filename);
            $exploda_path = explode("/",$filename);

            if ($exploda_path[4] != "" && $exploda_path[4] != "Thumbs.db") {
                echo $exploda_path[4] . " ----------> $exploda_path[3] ---------> $exploda_path[2] <br>"; //nome file  ----> tipo -----> data precipe
                echo $filename . "<br>";

                //inserisco nella tabella allegati Data e $item
                $query2 = "INSERT INTO `msc_allegati`(`data`, `file`) VALUES (NOW(),'". $filename ."')";
                $res2 = mysqli_query($db, $query2);

                $last_id = mysqli_insert_id($db); //ultimo id inserito


                $query = "SELECT * FROM msc_precipe as p WHERE data = '".$exploda_path[2]."'";
                $res = mysqli_query($db, $query);
                $id_precipe = "";
                if(mysqli_num_rows($res) >= 1) {
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



function setIdFascicoli() {
    
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
		
    if(mysqli_num_rows($res) >= 1) {
        while($row = mysqli_fetch_array($res)) {

			$query2 = 'UPDATE `msc_registri` SET `id_fascicoli`= "'.$row['idF'].'"  WHERE id = '.$row['idR'];
            
            echo $query2 . "<br>";
            $res2 = mysqli_query($db, $query2);
        }
    }
}




function raggruppaPreCipeData() {
    global $db;

    $query = "SELECT Data_PreCipe
              FROM TABLE33_precipe_full 
              GROUP BY Data_PreCipe";
    $res = mysqli_query($db, $query);

    if(mysqli_num_rows($res) >= 1) {
        while($row = mysqli_fetch_array($res)) {
            $query2 = 'INSERT INTO msc_precipe SET data = "'.$row['Data_PreCipe'].'"';

            echo $query2 . "<br>";
            $res2 = mysqli_query($db, $query2);
        }
    }
}


function setOrdiniPreCipe() { //temporaneamente deve esistere la data anche nella tabella ordini!
    global $db;

    $query = "SELECT *
              FROM msc_precipe";
    $res = mysqli_query($db, $query);

    if(mysqli_num_rows($res) >= 1) {
        while($row = mysqli_fetch_array($res)) {
            $query2 = 'SELECT * FROM TABLE33_precipe_full WHERE Data_PreCipe = "'.$row['data'].'"';
            $res2 = mysqli_query($db, $query2);

            if(mysqli_num_rows($res2) >= 1) {
                while ($row2 = mysqli_fetch_array($res2)) {
                    $argomenti = "";
                    $query_argomenti = 'SELECT * FROM msc_argomenti WHERE codice = "'.$row2['Codice_ArgomentoPreCipe'].'"';
                    $res_argomenti = mysqli_query($db, $query_argomenti);
                    while ($row_argomenti = mysqli_fetch_array($res_argomenti)) {
                        $argomenti = $row_argomenti['id'];
                    }

                    $titolari = "";
                    $query_titolari = 'SELECT * FROM msc_titolari WHERE codice = "'.$row2['Codice_Titolario'].'"';
                    $res_titolari = mysqli_query($db, $query_titolari);
                    while ($row_titolari = mysqli_fetch_array($res_titolari)) {
                        $titolari = $row_titolari['id'];
                    }

                    $fascicoli = "";
                    $query_fascicoli = 'SELECT * FROM msc_fascicoli WHERE codice_titolario = "'.$row2['Codice_Titolario'].'" AND numero_fascicolo = "'.$row2['Numero_Fascicolo'].'"';
                    $res_fascicoli = mysqli_query($db, $query_fascicoli);
                    while ($row_fascicoli = mysqli_fetch_array($res_fascicoli)) {
                        $fascicoli = $row_fascicoli['id'];
                    }

                    $uffici = "";
                    $query_uffici = 'SELECT * FROM msc_uffici WHERE codice = "'.$row2['Codice_Ufficio'].'"';
                    $res_uffici = mysqli_query($db, $query_uffici);
                    while ($row_uffici = mysqli_fetch_array($res_uffici)) {
                        $uffici = $row_uffici['id'];
                    }

                    $risultanze = "";
                    $query_risultanze = 'SELECT * FROM msc_risultanze WHERE codice = "'.$row2['Codice_RisultanzaPreCipe'].'"';
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
                        numero_odg = "' . $row2['Numero_OdgPreCipe'] . '", 
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






function setRegistriPrecipe() {
    global $db;

    $query = "SELECT * 
              FROM TABLE33_precipe_full";
    $res = mysqli_query($db, $query);

    if(mysqli_num_rows($res) >= 1) {
        while($row = mysqli_fetch_array($res)) {

            if ($row['Codice_Registro'] != "") {
                $query2 = 'INSERT INTO msc_rel_registri_odg SET id_precipe = "'.$row['Codice_PreCipe'].'", id_registri = "'.$row['Codice_Registro'].'" ';
                echo $query2 . "<br>";
                $res2 = mysqli_query($db, $query2);
            }

            if ($row['Codice_Registro2'] != "") {
                $query2 = 'INSERT INTO msc_rel_registri_odg SET id_precipe = "'.$row['Codice_PreCipe'].'", id_registri = "'.$row['Codice_Registro2'].'" ';
                echo $query2 . "<br>";
                $res2 = mysqli_query($db, $query2);
            }

            if ($row['Codice_Registro3'] != "") {
                $query2 = 'INSERT INTO msc_rel_registri_odg SET id_precipe = "'.$row['Codice_PreCipe'].'", id_registri = "'.$row['Codice_Registro3'].'" ';
                echo $query2 . "<br>";
                $res2 = mysqli_query($db, $query2);
            }

        }
    }
}




function checkRegistriFascicoli() {
    global $db;

    $query = "SELECT * 
              FROM TABLE33_precipe_full";
    $res = mysqli_query($db, $query);

    if(mysqli_num_rows($res) >= 1) {
        while($row = mysqli_fetch_array($res)) {



        }
    }
}




?>