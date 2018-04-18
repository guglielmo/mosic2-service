<?php

//########################## ADEMPIMENTI 

$query1 ="SELECT COUNT(*) as c FROM Adempimenti";
$res1 = mysqli_query($db, $query1) or  mysqli_error($db);
$rowsAdempimenti = mysqli_fetch_array($res1);

$query1 ="SELECT COUNT(*) as c FROM AdempimentiAmbiti";
$res1 = mysqli_query($db, $query1) or  mysqli_error($db);
$rowsAdempimentiAmbiti = mysqli_fetch_array($res1);

$query1 ="SELECT COUNT(*) as c FROM AdempimentiTipologie";
$res1 = mysqli_query($db, $query1) or  mysqli_error($db);
$rowsAdempimentiTipologie = mysqli_fetch_array($res1);

$query1 ="SELECT COUNT(*) as c FROM AdempimentiAzioni";
$res1 = mysqli_query($db, $query1) or  mysqli_error($db);
$rowsAdempimentiAzioni = mysqli_fetch_array($res1);

//########################## AMMINISTRAZIONI 

$query2 ="SELECT COUNT(*) as c FROM Amministrazioni";
$res2 = mysqli_query($db, $query2) or  mysqli_error($db);
$rowsAmministrazioni = mysqli_fetch_array($res2);


//########################## ARGOMENTI CIPE 

$query3 ="SELECT COUNT(*) as c FROM ArgomentiCipe";
$res3 = mysqli_query($db, $query3) or  mysqli_error($db);
$rowsArgomentiCipe = mysqli_fetch_array($res3);


//########################## CIPE 

$query4 ="SELECT COUNT(*) as c FROM Cipe";
$res4 = mysqli_query($db, $query4) or  mysqli_error($db);
$rowsCipe = mysqli_fetch_array($res4);


//########################## DATI DELIBERA 

$query5 ="SELECT COUNT(*) as c FROM DatiDelibera";
$res5 = mysqli_query($db, $query5) or  mysqli_error($db);
$rowsDatiDelibera = mysqli_fetch_array($res5);


//########################## ESITI CIPE 

$query6 ="SELECT COUNT(*) as c FROM EsitiCipe";
$res6 = mysqli_query($db, $query6) or  mysqli_error($db);
$rowsEsitiCipe = mysqli_fetch_array($res6);


//########################## FIRMATARI 

$query7 ="SELECT COUNT(*) as c FROM Firmatari";
$res7 = mysqli_query($db, $query7) or  mysqli_error($db);
$rowsFirmatari = mysqli_fetch_array($res7);


//########################## LIVELLI 

$query8 ="SELECT COUNT(*) as c FROM Livelli";
$res8 = mysqli_query($db, $query8) or  mysqli_error($db);
$rowsLivelli = mysqli_fetch_array($res8);


//########################## PRECIPE 

$query9 ="SELECT COUNT(*) as c FROM PreCipe";
$res9 = mysqli_query($db, $query9) or  mysqli_error($db);
$rowsPreCipe = mysqli_fetch_array($res9);


//########################## UTENTI 

$query10 ="SELECT COUNT(*) as c FROM PwUtenti";
$res10 = mysqli_query($db, $query10) or  mysqli_error($db);
$rowsPwUtenti = mysqli_fetch_array($res10);


//########################## REGISTRO 

$query11 ="SELECT COUNT(*) as c FROM Registro";
$res11 = mysqli_query($db, $query11) or  mysqli_error($db);
$rowsRegistro = mysqli_fetch_array($res11);


//########################## REPERTORIO 

$query12 ="SELECT COUNT(*) as c FROM Repertorio";
$res12 = mysqli_query($db, $query12) or  mysqli_error($db);
$rowsRepertorio = mysqli_fetch_array($res12);


//########################## RISULTANZE PRECIPE 

$query13 ="SELECT COUNT(*) as c FROM Risultanze_PreCipe";
$res13 = mysqli_query($db, $query13) or  mysqli_error($db);
$rowsRisultanze_PreCipe = mysqli_fetch_array($res13);

//########################## RISULTANZE SEDUTE CIPE 

$query14 ="SELECT COUNT(*) as c FROM Sedute_Cipe";
$res14 = mysqli_query($db, $query14) or  mysqli_error($db);
$rowsSedute_Cipe = mysqli_fetch_array($res14);

//########################## RISULTANZE SEDUTE CIPE 

$query15 ="SELECT COUNT(*) as c FROM SottoFascicoli";
$res15 = mysqli_query($db, $query15) or  mysqli_error($db);
$rowsSottoFascicoli = mysqli_fetch_array($res15);

//########################## RISULTANZE TIPO ARGOMENTI CIPE 

$query16 ="SELECT COUNT(*) as c FROM TipoArgomentiCipe";
$res16 = mysqli_query($db, $query16) or  mysqli_error($db);
$rowsTipoArgomentiCipe = mysqli_fetch_array($res16);

//########################## RISULTANZE TIPO ESITI CIPE 

$query17 ="SELECT COUNT(*) as c FROM TipoEsitiCipe";
$res17 = mysqli_query($db, $query17) or  mysqli_error($db);
$rowsTipoEsitiCipe = mysqli_fetch_array($res17);

//########################## RISULTANZE TIPO FIRMATARI 

$query18 ="SELECT COUNT(*) as c FROM TipoFirmatari";
$res18 = mysqli_query($db, $query18) or  mysqli_error($db);
$rowsTipoFirmatari = mysqli_fetch_array($res18);

//########################## RISULTANZE TITOLARIO 

$query19 ="SELECT COUNT(*) as c FROM Titolario";
$res19 = mysqli_query($db, $query19) or  mysqli_error($db);
$rowsTitolario = mysqli_fetch_array($res19);

//########################## RISULTANZE UFFICI DIPE 

$query20 ="SELECT COUNT(*) as c FROM UfficiDipe";
$res20 = mysqli_query($db, $query20) or  mysqli_error($db);
$rowsUfficiDipe = mysqli_fetch_array($res20);


?>