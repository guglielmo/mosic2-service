<?php

$pathFiles = "fileCSV/";

//########################## ADEMPIMENTI

$queryDelete1 ="DROP TABLE Adempimenti";
mysqli_query($db, $queryDelete1) or  mysqli_error($db);


//########################## AMMINISTRAZIONI

$queryDelete2 ="DROP TABLE Amministrazioni";
mysqli_query($db, $queryDelete2) or  mysqli_error($db);


//########################## ARGOMENTI CIPE

$queryDelete3 ="DROP TABLE ArgomentiCipe";
mysqli_query($db, $queryDelete3) or  mysqli_error($db);


//########################## CIPE

$queryDelete4 ="DROP TABLE Cipe";
mysqli_query($db, $queryDelete4) or  mysqli_error($db);


//########################## DATI DELIBERA

$queryDelete5 ="DROP TABLE DatiDelibera";
mysqli_query($db, $queryDelete5) or  mysqli_error($db);


//########################## ESITI CIPE

$queryDelete6 ="DROP TABLE EsitiCipe";
mysqli_query($db, $queryDelete6) or  mysqli_error($db);


//########################## FIRMATARI

$queryDelete7 ="DROP TABLE Firmatari";
mysqli_query($db, $queryDelete7) or  mysqli_error($db);


//########################## LIVELLI

$queryDelete8 ="DROP TABLE Livelli";
mysqli_query($db, $queryDelete8) or  mysqli_error($db);


//########################## PRECIPE

$queryDelete9 ="DROP TABLE PreCipe";
mysqli_query($db, $queryDelete9) or  mysqli_error($db);


//########################## UTENTI

$queryDelete10 ="DROP TABLE PwUtenti";
mysqli_query($db, $queryDelete10) or  mysqli_error($db);


//########################## REGISTRO

$queryDelete11 ="DROP TABLE Registro";
mysqli_query($db, $queryDelete11) or  mysqli_error($db);


//########################## REPERTORIO

$queryDelete12 ="DROP TABLE Repertorio";
mysqli_query($db, $queryDelete12) or  mysqli_error($db);


//########################## RISULTANZE PRECIPE

$queryDelete13 ="DROP TABLE Risultanze_PreCipe";
mysqli_query($db, $queryDelete13) or  mysqli_error($db);


//########################## SEDUTE CIPE

$queryDelete14 ="DROP TABLE Sedute_Cipe";
mysqli_query($db, $queryDelete14) or  mysqli_error($db);


//########################## SOTTOFASCICOLI

$queryDelete15 ="DROP TABLE SottoFascicoli";
mysqli_query($db, $queryDelete15) or  mysqli_error($db);

//########################## TIPO ARGOMENTI CIPE

$queryDelete16 ="DROP TABLE TipoArgomentiCipe";
mysqli_query($db, $queryDelete16) or  mysqli_error($db);

//########################## TIPO ESITI CIPE

$queryDelete17 ="DROP TABLE TipoEsitiCipe";
mysqli_query($db, $queryDelete17) or  mysqli_error($db);

//########################## TIPO FIRMATARI

$queryDelete18 ="DROP TABLE TipoFirmatari";
mysqli_query($db, $queryDelete18) or  mysqli_error($db);

//########################## TIPO TITOLARIO

$queryDelete19 ="DROP TABLE Titolario";
mysqli_query($db, $queryDelete19) or  mysqli_error($db);

//########################## TIPO TITOLARIO

$queryDelete20 ="DROP TABLE UfficiDipe";
mysqli_query($db, $queryDelete20) or  mysqli_error($db);


?>