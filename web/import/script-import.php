<?php

    require_once "../config-web.php";

    require_once "function.php";


    if (isset($_REQUEST['step1'])) {
        require_once "create-table.php";
        header("Location: " . $_SERVER['PHP_SELF']);
    }
    
    if (isset($_REQUEST['step2'])) {

        createTipoArgomentiCipe();
        createTipoEsitiCipe();

        createFirmatari();
        createTipoFirmatari();
        createRuoliCipe(); //per gli utenti

        createLastUpdates(); //per gli utenti
        createGroups();

        ################################ REGISTRI / FASCICOLI
        createTitolari();
        createMittenti();
        createRegistri();

        createAmministrazioni();

        createEsitiCipe();
        createFascicoli();
        setIdFascicoliRegistri();

        setRelAmministrazioniFascicoli();
        setRelAmministrazioniRegistri();

        ################################ PRECIPE
        raggruppaPreCipe();

        createArgomenti();
        createUffici();
        createRisultanze();

        setOrdiniPreCipe();
        setRegistriPrecipe();
        setUfficiPreCipeOdg();

        ################################ CIPE
        raggruppaCipe();
        setOrdiniCipe();
        setRegistriCipe();
        setUfficiCipeOdg();

        ################################ DELIBERE
        setDelibere();
        setUfficiDelibere();
        setCorteDeiContiDelibere();
        setFunzionariDelibere();
        setDateDelibereGiorni();


        ################################ UTENTI
        createUtenti();

        ################################ ADEMPIMENTI
        createAdempimenti(); // da aggiornare l'utente!
      
        $fineStep2 = "true";
    }
    
    if (isset($_REQUEST['step3'])) {
        renameFile("../files/REGISTRO_MOSIC");
        gestioneFilesRegistri($filePath , "../files/REGISTRO_MOSIC"); //(+ sottofasicoli) và usata sul server in quanto ci devono essere fisicamente i file
        gestioneFilesPreCipe($filePath, "../files/RIUNIONI_PRECIPE");
        gestioneFilesCipe($filePath, "../files/SEDUTE_CIPE");
        setAllegatiDelibere($filePath, "../files/DELIBERE/per-anno");
        $fineStep3 = "true";
    }
    
    if (isset($_REQUEST['step4'])) {
        require_once "delete-table.php";
        header("Location: " . $_SERVER['PHP_SELF']);
    }

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
                    <h3 class="panel-title">Tabelle presenti</h3>
                </div>
                <div class="panel-body">
                	
                    <?php if ($rowsAdempimenti > 0) { ?>
                        <div class="row">
                            Tabella Adempimenti --> <?= $rowsAdempimenti[0] ?> righe
                        </div>
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
                    
					<?php if ($fineStep2 == "true") { ?>
                        <div class="row">
                            Fine step 2
                        </div>
                    <?php } ?>

                    <?php if ($fineStep3 == "true") { ?>
                        <div class="row">
                            Fine step 3
                        </div>
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