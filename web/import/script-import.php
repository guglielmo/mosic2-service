<?php

    require_once "../config-web.php";

    require_once "function.php";

if (isset($_REQUEST['step1'])) {
        require_once "create-table.php";
        header("Location: " . $_SERVER['PHP_SELF']);
    }
    
    if (isset($_REQUEST['step2'])) {
        $tempo1 = microtime(true);

       $return = createTipoArgomentiCipe(); if(isset($return)) { $fineStep2 = $return; goto jump;}
        $return = createTipoEsitiCipe(); if(isset($return)) { $fineStep2 = $return; goto jump;}
        $return = createFirmatari(); if(isset($return)) { $fineStep2 = $return; goto jump;}
        $return = createTipoFirmatari(); if(isset($return)) { $fineStep2 = $return; goto jump;}
        $return = createRuoliCipe(); if(isset($return)) { $fineStep2 = $return; goto jump;} //per gli utenti
        $return = createLastUpdates(); if(isset($return)) { $fineStep2 = $return; goto jump;} //per gli utenti


        //################################ REGISTRI / FASCICOLI
        $return = createTitolari(); if(isset($return)) { $fineStep2 = $return; goto jump;}
        $return = createMittenti(); if(isset($return)) { $fineStep2 = $return; goto jump;}
        $return = createRegistri(); if(isset($return)) { $fineStep2 = $return; goto jump;}

        $return = createAmministrazioni(); if(isset($return)) { $fineStep2 = $return; goto jump;}

        $return = createEsitiCipe(); if(isset($return)) { $fineStep2 = $return; goto jump;}
        $return = createFascicoli(); if(isset($return)) { $fineStep2 = $return; goto jump;}
        $return = setIdFascicoliRegistri(); if(isset($return)) { $fineStep2 = $return; goto jump;}

        $return = setRelAmministrazioniFascicoli(); if(isset($return)) { $fineStep2 = $return; goto jump;}
        $return = setRelAmministrazioniRegistri(); if(isset($return)) { $fineStep2 = $return; goto jump;}

        //################################ PRECIPE
        $return = raggruppaPreCipe(); if(isset($return)) { $fineStep2 = $return; goto jump;}
        $return = createArgomenti(); if(isset($return)) { $fineStep2 = $return; goto jump;}
        $return = createUffici(); if(isset($return)) { $fineStep2 = $return; goto jump;}
        $return = createRisultanze(); if(isset($return)) { $fineStep2 = $return; goto jump;}
        $return = setOrdiniPreCipe(); if(isset($return)) { $fineStep2 = $return; goto jump;}
        $return = setRegistriPrecipe(); if(isset($return)) { $fineStep2 = $return; goto jump;}
        $return = setUfficiPreCipeOdg(); if(isset($return)) { $fineStep2 = $return; goto jump;}

        //################################ CIPE
        $return = raggruppaCipe(); if(isset($return)) { $fineStep2 = $return; goto jump;}
        $return = setOrdiniCipe(); if(isset($return)) { $fineStep2 = $return; goto jump;}
        $return = setRegistriCipe(); if(isset($return)) { $fineStep2 = $return; goto jump;}
        $return = setUfficiCipeOdg(); if(isset($return)) { $fineStep2 = $return; goto jump;}

        //################################ DELIBERE
        $return = setDelibere(); if(isset($return)) { $fineStep2 = $return; goto jump;}
        $return = setUfficiDelibere(); if(isset($return)) { $fineStep2 = $return; goto jump;}
        $return = setCorteDeiContiDelibere(); if(isset($return)) { $fineStep2 = $return; goto jump;}
        $return = setFunzionariDelibere(); if(isset($return)) { $fineStep2 = $return; goto jump;}
        $return = setDateDelibereGiorni(); if(isset($return)) { $fineStep2 = $return; goto jump;}


        //################################ UTENTI
        $return = createUtenti(); if(isset($return)) { $fineStep2 = $return; goto jump;}

        //################################ ADEMPIMENTI
        $return = createAdempimenti(); if(isset($return)) { $fineStep2 = $return; goto jump;} // da aggiornare l'utente!

        $tempo1 = microtime(true) - $tempo1;
        $fineStep2 = "Fine step 2 <br><br> (tempo impiegato): ". $tempo1 . " secondi";
    }
    
    if (isset($_REQUEST['step3'])) {
        $fineStep3 = array();

        $tempo1 = microtime(true);
        //sposto le cartelle degli anni (delibere) nella cartella DELIBERE
        renameWithNestedMkdir("../files/1997", "../files/DELIBERE/per-anno/1997");
        renameWithNestedMkdir("../files/1998", "../files/DELIBERE/per-anno/1998");
        renameWithNestedMkdir("../files/1999", "../files/DELIBERE/per-anno/1999");
        renameWithNestedMkdir("../files/2000", "../files/DELIBERE/per-anno/2000");
        renameWithNestedMkdir("../files/2001", "../files/DELIBERE/per-anno/2001");
        renameWithNestedMkdir("../files/2002", "../files/DELIBERE/per-anno/2002");
        renameWithNestedMkdir("../files/2003", "../files/DELIBERE/per-anno/2003");
        renameWithNestedMkdir("../files/2004", "../files/DELIBERE/per-anno/2004");
        renameWithNestedMkdir("../files/2005", "../files/DELIBERE/per-anno/2005");
        renameWithNestedMkdir("../files/2006", "../files/DELIBERE/per-anno/2006");
        renameWithNestedMkdir("../files/2007", "../files/DELIBERE/per-anno/2007");
        renameWithNestedMkdir("../files/2008", "../files/DELIBERE/per-anno/2008");
        renameWithNestedMkdir("../files/2009", "../files/DELIBERE/per-anno/2009");
        renameWithNestedMkdir("../files/2010", "../files/DELIBERE/per-anno/2010");
        renameWithNestedMkdir("../files/2011", "../files/DELIBERE/per-anno/2011");
        renameWithNestedMkdir("../files/2012", "../files/DELIBERE/per-anno/2012");
        renameWithNestedMkdir("../files/2013", "../files/DELIBERE/per-anno/2013");
        renameWithNestedMkdir("../files/2014", "../files/DELIBERE/per-anno/2014");
        renameWithNestedMkdir("../files/2015", "../files/DELIBERE/per-anno/2015");
        renameWithNestedMkdir("../files/2016", "../files/DELIBERE/per-anno/2016");
        renameWithNestedMkdir("../files/2017", "../files/DELIBERE/per-anno/2017");


        //renameFile("../files/REGISTRO MOSIC");
        //renameFile("../files/RIUNIONI_PRECIPE");
        //renameFile("../files/SEDUTE_CIPE");
        //renameFile("../files/DELIBERE");

        $return = gestioneFilesRegistri($filePath , "../files/REGISTRO MOSIC");
        if(isset($return)) {
            if (is_array($return)) {
                $fineStep3 = array_merge($fineStep3, $return);
            } else {
                $fineStep3 = $return;
                goto jump;
            }
        } //(+ sottofasicoli) và usata sul server in quanto ci devono essere fisicamente i file

        $return = gestioneFilesPreCipe($filePath, "../files/RIUNIONI_PRECIPE"); if(isset($return)) { $fineStep3 = $return; goto jump;}
        $return = gestioneFilesCipe($filePath, "../files/SEDUTE_CIPE"); if(isset($return)) { $fineStep3 = $return; goto jump;}
        $return = setAllegatiDelibere($filePath, "../files/DELIBERE/per-anno"); if(isset($return)) { $fineStep3 = $return; goto jump;}

        $tempo1 = microtime(true) - $tempo1;

        $fineStep3[] = "Fine step 3 <br><br> (tempo impiegato): ". $tempo1 . " secondi";
    }

    if (isset($_REQUEST['step4'])) {
        require_once "delete-table.php";
        //header("Location: " . $_SERVER['PHP_SELF']);
        $fineStep4 ="Tabelle eliminate con successo.";
    }

    jump:

    require_once "view-table.php";

?>


<html lang="en"><head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>SCRIPT IMPORT</title>

    <!-- Bootstrap core CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
  </head>

  <body>

    <div class="container">
    
    	<div class="row">
        	<h2>Import dati Mo.Si.C 2.0</h2>
        </div>
    
        <div class="row">
            <br/>
            <form action="" method="post">
                    <button type="submit" name="step1" class="btn btn-primary btn-block"><strong>STEP 1</strong> - Crea e carica i CSV originali (sovrascrive i vecchi se presenti)</button>
                    <button type="submit" name="step2" class="btn btn-primary btn-block"><strong>STEP 2</strong> - Esegui script collega tabelle</button>
                    <button type="submit" name="step3" class="btn btn-primary btn-block"><strong>STEP 3</strong> - Sanitize File e gestione allegati</button>
                    <button type="submit" name="step4" class="btn btn-primary btn-block"><strong>STEP 4</strong> - Elimina tabelle orginali</button>
            </form>
        </div>
        
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Tabelle presenti (originali) da eliminare al termine di tutte le procedure (Step 4)</h3>
                </div>
                <div class="panel-body">
                	
                    <?php if ($rowsAdempimenti > 0 && !isset($fineStep4)) { ?>
                            Tabella Adempimenti --> <?= $rowsAdempimenti[0] ?> righe <br/>
                            Tabella Amministrazioni --> <?= $rowsAmministrazioni[0] ?> righe  <br/>
                            Tabella ArgomentiCipe --> <?= $rowsArgomentiCipe[0] ?> righe  <br/>
                            Tabella Cipe --> <?= $rowsCipe[0] ?> righe  <br/>
                            Tabella DatiDelibera --> <?= $rowsDatiDelibera[0] ?> righe  <br/>
                            Tabella EsitiCipe --> <?= $rowsEsitiCipe[0] ?> righe  <br/>
                            Tabella Firmatari --> <?= $rowsFirmatari[0] ?> righe  <br/>
                            Tabella Livelli --> <?= $rowsLivelli[0] ?> righe  <br/>
                            Tabella PreCipe --> <?= $rowsPreCipe[0] ?> righe  <br/>
                            Tabella PwUtenti --> <?= $rowsPwUtenti[0] ?> righe  <br/>
                            Tabella Registro --> <?= $rowsRegistro[0] ?> righe  <br/>
                            Tabella Repertorio --> <?= $rowsRepertorio[0] ?> righe  <br/>
                            Tabella Risultanze_PreCipe --> <?= $rowsRisultanze_PreCipe[0] ?> righe  <br/>
                            Tabella Sedute_Cipe --> <?= $rowsSedute_Cipe[0] ?> righe  <br/>
                            Tabella SottoFascicoli --> <?= $rowsSottoFascicoli[0] ?> righe  <br/>
                            Tabella TipoArgomentiCipe --> <?= $rowsTipoArgomentiCipe[0] ?> righe  <br/>
                            Tabella TipoEsitiCipe --> <?= $rowsTipoEsitiCipe[0] ?> righe  <br/>
                            Tabella TipoFirmatari --> <?= $rowsTipoFirmatari[0] ?> righe  <br/>
                            Tabella Titolario --> <?= $rowsTitolario[0] ?> righe  <br/>
                            Tabella UfficiDipe --> <?= $rowsUfficiDipe[0] ?> righe  <br/>
                    <?php } ?>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Messaggi</h3>
                </div>
                <div class="panel-body">
                    
					<?php if (isset($fineStep2)) { ?>
                            <?= $fineStep2 ?>
                    <?php } ?>

                    <?php if (isset($fineStep3)) { ?>
                        <?php if (is_array($fineStep3)) {
                            foreach ($fineStep3 as $item) {
                                echo $item;
                            }
                        } else {
                            echo $fineStep3;
                        }
                        ?>
                    <?php } ?>

                    <?php if (isset($fineStep4)) { ?>
                        <?= $fineStep4 ?>
                    <?php } ?>


                </div>
            </div>
        </div>

    </div>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script>window.jQuery || document.write('&lt;script src="../../assets/js/vendor/jquery.min.js"&gt;&lt;\/script&gt;')</script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  

</body>
</html>

<?php  
    //echo "<br/>";
    //echo "<br/>";
    //echo "FINE";
?>