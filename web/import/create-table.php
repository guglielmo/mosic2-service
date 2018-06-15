<?php

$pathFiles = "fileCSV/";

//############################################################## TABLE ADEMPIMENTI

//$table1 = "Adempimenti";
//$queryDelete1 ="DROP TABLE " . $table1;
//mysqli_query($db, $queryDelete1) or  mysqli_error($db);
//
//$queryCreate1 = "CREATE TABLE `Adempimenti` (
//  `Codice_Adempimento` int(11) DEFAULT NULL,
//  `Progressivo_Adempimento` int(11) DEFAULT NULL,
//  `Codice_Scheda` int(11) DEFAULT NULL,
//  `Codice_Delibera` int(11) DEFAULT NULL,
//  `Data_Delibera` datetime DEFAULT NULL,
//  `Numero_Delibera` int(11) DEFAULT NULL,
//  `Descrizione_Adempimento` longtext DEFAULT NULL,
//  `Codice_DescrizioneAdempimento` int(11) DEFAULT NULL,
//  `Codice_FonteAdempimento` int(11) DEFAULT NULL,
//  `Codice_EsitoAdempimento` int(11) DEFAULT NULL,
//  `Data_Scadenza_Adempimento` datetime DEFAULT NULL,
//  `Giorni_Scadenza_Adempimento` int(11) DEFAULT NULL,
//  `Mesi_Scadenza_Adempimento` int(11) DEFAULT NULL,
//  `Anni_Scadenza_Adempimento` int(11) DEFAULT NULL,
//  `Vincolo_Adempimento` int(11) DEFAULT NULL,
//  `Note_Adempimento` longtext,
//  `Utente_Modifica` varchar(255) DEFAULT NULL,
//  `Data_UtenteModifica` datetime DEFAULT NULL,
//  `Ora_UtenteModifica` datetime DEFAULT NULL
//) ENGINE=InnoDB DEFAULT CHARSET=utf8";
//mysqli_query($db, $queryCreate1) or die( mysqli_error($db));
//
//$queryLoad1 = 'LOAD DATA LOCAL INFILE "'.$pathFiles . $table1 .'.csv"
//        INTO TABLE '.$table1.'
//        FIELDS TERMINATED by \',\'
//        ENCLOSED BY \'"\'
//        LINES TERMINATED BY \'\n\'
//        IGNORE 1 LINES';
//mysqli_query($db, $queryLoad1) or die( mysqli_error($db));

$table1 = "Adempimenti";
$queryDelete1 ="DROP TABLE " . $table1;
mysqli_query($db, $queryDelete1) or  mysqli_error($db);
$queryCreate1 = "CREATE TABLE `Adempimenti` (
              `id` int(11) DEFAULT NULL,
              `Istruttore` varchar(255) DEFAULT NULL,
              `Progressivo` int(11) DEFAULT NULL,
              `Numero_Delibera` int(11) DEFAULT NULL,
              `Anno` int(11) DEFAULT NULL,
              `Seduta` date DEFAULT NULL,
              `Materia` varchar(255) DEFAULT NULL,
              `Argomento` longtext DEFAULT NULL,
              `Fondo_norma` varchar(255) DEFAULT NULL,
              `Ambito` varchar(255) DEFAULT NULL,
              `Localizzazione` varchar(255) DEFAULT NULL,
              `CUP` varchar(255) DEFAULT NULL,
              `Riferimento` varchar(255) DEFAULT NULL,
              `Descrizione` longtext DEFAULT NULL,
              `Tipologia` varchar(255) DEFAULT NULL,
              `Azione` varchar(255) DEFAULT NULL,
              `Mancato_assolvimento` varchar(255) DEFAULT NULL,
              `Amministrazione` varchar(255) DEFAULT NULL,
              `Norme_delibere` varchar(255) DEFAULT NULL,
              `Scadenza` date DEFAULT NULL,
              `Destinatario` varchar(255) DEFAULT NULL,
              `Struttura` varchar(255) DEFAULT NULL,
              `Adempiuto` varchar(255) DEFAULT NULL,
              `Periodicita` int(11) DEFAULT NULL,
              `Pluriennalita` int(11) DEFAULT NULL,
              `NOTE` longtext DEFAULT NULL

        ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
mysqli_query($db, $queryCreate1) or die( mysqli_error($db));

$pathFiles = "fileCSV/";
$queryLoad1 = 'LOAD DATA LOCAL INFILE "'.$pathFiles . $table1 .'.csv"
        INTO TABLE '.$table1.'
        FIELDS TERMINATED by \',\'
        ENCLOSED BY \'"\'
        LINES TERMINATED BY \'\n\'
        IGNORE 1 LINES';
mysqli_query($db, $queryLoad1) or die( mysqli_error($db));



//############################################################## TABLE AMMINISTRAZIONI

$table2 = "Amministrazioni";
$queryDelete2 ="DROP TABLE " . $table2;
mysqli_query($db, $queryDelete2) or  mysqli_error($db);

$queryCreate2 = "CREATE TABLE `Amministrazioni` (
  `Codice_Amministrazione` int(11) DEFAULT NULL,
  `Descrizione_Amministrazione` varchar(255) DEFAULT NULL,
  `Codice_Genere` int(11) DEFAULT NULL,
  `Aggiornamento_Amministrazione` datetime DEFAULT NULL,
  `Utente_Amministrazione` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
mysqli_query($db, $queryCreate2) or die( mysqli_error($db));


$queryLoad2 = 'LOAD DATA LOCAL INFILE "'.$pathFiles . $table2 .'.csv"
        INTO TABLE '.$table2.'
        FIELDS TERMINATED by \',\'
        ENCLOSED BY \'"\'
        LINES TERMINATED BY \'\n\'
        IGNORE 1 LINES';
mysqli_query($db, $queryLoad2) or die( mysqli_error($db));


//############################################################## TABLE ARGOMENTI CIPE

$table3 = "ArgomentiCipe";
$queryDelete3 ="DROP TABLE " . $table3;
mysqli_query($db, $queryDelete3) or  mysqli_error($db);

$queryCreate3 = "CREATE TABLE `ArgomentiCipe` (
  `Codice_ArgomentoCipe` int(11) DEFAULT NULL,
  `Descrizione_ArgomentoCipe` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
mysqli_query($db, $queryCreate3) or die( mysqli_error($db));


$queryLoad3 = 'LOAD DATA LOCAL INFILE "'.$pathFiles . $table3 .'.csv"
        INTO TABLE '.$table3.'
        FIELDS TERMINATED by \',\'
        ENCLOSED BY \'"\'
        LINES TERMINATED BY \'\n\'
        IGNORE 1 LINES';
mysqli_query($db, $queryLoad3) or die( mysqli_error($db));


//############################################################## TABLE CIPE

$table4 = "Cipe";
$queryDelete4 ="DROP TABLE " . $table4;
mysqli_query($db, $queryDelete4) or  mysqli_error($db);

$queryCreate4 = "CREATE TABLE `Cipe` (
  `Codice_Cipe` int(11) DEFAULT NULL,
  `Data_Cipe` datetime DEFAULT NULL,
  `Progressivo_Cipe` int(11) DEFAULT NULL,
  `Codice_Titolario` int(11) DEFAULT NULL,
  `Numero_Fascicolo` int(11) DEFAULT NULL,
  `Numero_SottoFascicolo` int(11) DEFAULT NULL,
  `Codice_Registro` int(11) DEFAULT NULL,
  `Codice_Registro2` int(11) DEFAULT NULL,
  `Codice_Registro3` int(11) DEFAULT NULL,
  `Codice_TipoArgomentoCipe` int(11) DEFAULT NULL,
  `Codice_ArgomentoCipe` int(11) DEFAULT NULL,
  `Numero_OdgCipe` varchar(255) DEFAULT NULL,
  `Oggetto_Cipe` longtext,
  `Codice_Ufficio` int(11) DEFAULT NULL,
  `Codice_RisultanzaCipe` int(11) DEFAULT NULL,
  `Codice_EsitoCipe` int(11) DEFAULT NULL,
  `Codice_TipoEsitoCipe` int(11) DEFAULT NULL,
  `Numero_Delibera` int(11) DEFAULT NULL,
  `Annotazioni_Cipe` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
mysqli_query($db, $queryCreate4) or die( mysqli_error($db));


$queryLoad4 = 'LOAD DATA LOCAL INFILE "'.$pathFiles . $table4 .'.csv"
        INTO TABLE '.$table4.'
        FIELDS TERMINATED by \',\'
        ENCLOSED BY \'"\'
        LINES TERMINATED BY \'\n\'
        IGNORE 1 LINES';
mysqli_query($db, $queryLoad4) or die( mysqli_error($db));



//############################################################## TABLE DATIDELIBERA

$table5 = "DatiDelibera";
$queryDelete5 ="DROP TABLE " . $table5;
mysqli_query($db, $queryDelete5) or  mysqli_error($db);

$queryCreate5 = "CREATE TABLE `DatiDelibera` (
  `Codice_Delibera` int(11) NOT NULL,
  `Data_Delibera` datetime DEFAULT NULL,
  `Numero_Delibera` int(11) DEFAULT NULL,
  `Numero_Verbale` int(11) DEFAULT NULL,
  `Codice_Settore` int(11) DEFAULT NULL,
  `Codice_Tipologia` int(11) DEFAULT NULL,
  `Finanziamento` int(11) DEFAULT NULL,
  `Codice_Funzionario` int(11) DEFAULT NULL,
  `Codice_Funzionario2` int(11) DEFAULT NULL,
  `Codice_Funzionario3` int(11) DEFAULT NULL,
  `Data_Consegna` datetime DEFAULT NULL,
  `Consegna_Scheda` tinyint(1) DEFAULT NULL,
  `Nota_Consegna` varchar(255) DEFAULT NULL,
  `Codice_Direttore` int(11) DEFAULT NULL,
  `Data_DirettoreInvio` datetime DEFAULT NULL,
  `Data_DirettoreRitorno` datetime DEFAULT NULL,
  `Nota_Direttore` varchar(255) DEFAULT NULL,
  `Invio_Ragioneria` tinyint(1) DEFAULT NULL,
  `Data_RagioneriaInvio` datetime DEFAULT NULL,
  `Data_RagioneriaRitorno` datetime DEFAULT NULL,
  `Nota_Ragioneria` varchar(255) DEFAULT NULL,
  `Invio_Mef` tinyint(1) DEFAULT NULL,
  `Data_MefInvio` datetime DEFAULT NULL,
  `Data_MefPec` datetime DEFAULT NULL,
  `Data_MefRitorno` datetime DEFAULT NULL,
  `Osservazioni_Mef` tinyint(1) DEFAULT NULL,
  `Nota_Mef` varchar(255) DEFAULT NULL,
  `Codice_Segretario` int(11) DEFAULT NULL,
  `Data_SegretarioInvio` datetime DEFAULT NULL,
  `Data_SegretarioRitorno` datetime DEFAULT NULL,
  `Nota_Segretario` varchar(255) DEFAULT NULL,
  `Codice_Presidente` int(11) DEFAULT NULL,
  `Data_PresidenteInvio` datetime DEFAULT NULL,
  `Data_PresidenteRitorno` datetime DEFAULT NULL,
  `Nota_Presidente` varchar(255) DEFAULT NULL,
  `Codice_Ufficio` int(11) DEFAULT NULL,
  `Codice_Ufficio2` int(11) DEFAULT NULL,
  `Codice_Ufficio3` int(11) DEFAULT NULL,
  `Codice_StatoDelibera` int(11) DEFAULT NULL,
  `Argomento` longtext,
  `Nota_CC` varchar(255) DEFAULT NULL,
  `Tipo_DocumentoCC` int(11) DEFAULT NULL,
  `Numero_InvioCC` varchar(255) DEFAULT NULL,
  `Data_InvioCC` datetime DEFAULT NULL,
  `Numero_RilievoCC` varchar(255) DEFAULT NULL,
  `Data_RilievoCC` datetime DEFAULT NULL,
  `Numero_RispostaCC` varchar(255) DEFAULT NULL,
  `Data_RispostaCC` datetime DEFAULT NULL,
  `Tipo_Rilievo` int(11) DEFAULT NULL,
  `Nota_RilievoCC` varchar(255) DEFAULT NULL,
  `Tipo_DocumentoCC2` int(11) DEFAULT NULL,
  `Numero_RilievoCC2` varchar(255) DEFAULT NULL,
  `Data_RilievoCC2` datetime DEFAULT NULL,
  `Numero_RispostaCC2` varchar(255) DEFAULT NULL,
  `Data_RispostaCC2` datetime DEFAULT NULL,
  `Tipo_Rilievo2` int(11) DEFAULT NULL,
  `Nota_RilievoCC2` varchar(255) DEFAULT NULL,
  `Tipo_DocumentoCC3` int(11) DEFAULT NULL,
  `Numero_RilievoCC3` varchar(255) DEFAULT NULL,
  `Data_RilievoCC3` datetime DEFAULT NULL,
  `Numero_RispostaCC3` varchar(255) DEFAULT NULL,
  `Data_RispostaCC3` datetime DEFAULT NULL,
  `Tipo_Rilievo3` int(11) DEFAULT NULL,
  `Nota_RilievoCC3` varchar(255) DEFAULT NULL,
  `Data_ConferenzaStatoRegioni` datetime DEFAULT NULL,
  `Numero_ConferenzaStatoRegioni` varchar(255) DEFAULT NULL,
  `Nota_ConferenzaStatoRegioni` varchar(255) DEFAULT NULL,
  `File_ConferenzaStatoRegioni` varchar(255) DEFAULT NULL,
  `A_Data_ConferenzaStatoRegioni` datetime DEFAULT NULL,
  `A_Numero_ConferenzaStatoRegioni` varchar(255) DEFAULT NULL,
  `A_Nota_ConferenzaStatoRegioni` varchar(255) DEFAULT NULL,
  `A_File_ConferenzaStatoRegioni` varchar(255) DEFAULT NULL,
  `Data_ConferenzaUnificata` datetime DEFAULT NULL,
  `Numero_ConferenzaUnificata` varchar(255) DEFAULT NULL,
  `Nota_ConferenzaUnificata` varchar(255) DEFAULT NULL,
  `File_ConferenzaUnificata` varchar(255) DEFAULT NULL,
  `A_Data_ConferenzaUnificata` datetime DEFAULT NULL,
  `A_Numero_ConferenzaUnificata` varchar(255) DEFAULT NULL,
  `A_Nota_ConferenzaUnificata` varchar(255) DEFAULT NULL,
  `A_File_ConferenzaUnificata` varchar(255) DEFAULT NULL,
  `Data_CommissioneParlamentare` datetime DEFAULT NULL,
  `Numero_CommissioneParlamentare` varchar(255) DEFAULT NULL,
  `Nota_CommissioneParlamentare` varchar(255) DEFAULT NULL,
  `File_CommissioneParlamentare` varchar(255) DEFAULT NULL,
  `A_Data_CommissioneParlamentare` datetime DEFAULT NULL,
  `A_Numero_CommissioneParlamentare` varchar(255) DEFAULT NULL,
  `A_Nota_CommissioneParlamentare` varchar(255) DEFAULT NULL,
  `A_File_CommissioneParlamentare` varchar(255) DEFAULT NULL,
  `Data_Parlamento` datetime DEFAULT NULL,
  `Nota_Parlamento` varchar(255) DEFAULT NULL,
  `File_Parlamento` varchar(255) DEFAULT NULL,
  `Registro_RegistrazioneCC` varchar(255) DEFAULT NULL,
  `Foglio_RegistrazioneCC` varchar(255) DEFAULT NULL,
  `Data_RegistrazioneCC` datetime DEFAULT NULL,
  `Numero_InvioGU` varchar(255) DEFAULT NULL,
  `Data_InvioGU` datetime DEFAULT NULL,
  `Tipo_GU` int(11) DEFAULT NULL,
  `Numero_GU` varchar(255) DEFAULT NULL,
  `Data_GU` datetime DEFAULT NULL,
  `Nota_GU` varchar(255) DEFAULT NULL,
  `Numero_EC` varchar(255) DEFAULT NULL,
  `Data_EC` datetime DEFAULT NULL,
  `Numero_Co` varchar(255) DEFAULT NULL,
  `Data_Co` datetime DEFAULT NULL,
  `Allegato_1` varchar(255) DEFAULT NULL,
  `Allegato_2` varchar(255) DEFAULT NULL,
  `Allegato_3` varchar(255) DEFAULT NULL,
  `Tipo_Registrazione` int(11) DEFAULT NULL,
  `Tipo_Pubblicazione` int(11) DEFAULT NULL,
  `Inviata_Ragioneria` tinyint(1) DEFAULT NULL,
  `Nota_Cipe` varchar(255) DEFAULT NULL,
  `Nota_ServizioCipe` varchar(255) DEFAULT NULL,
  `Giorni_RilievoCC` int(11) DEFAULT NULL,
  `Giorni_RilievoCC2` int(11) DEFAULT NULL,
  `Giorni_RilievoCC3` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
mysqli_query($db, $queryCreate5) or die( mysqli_error($db));

$queryLoad5 = 'LOAD DATA LOCAL INFILE "'.$pathFiles . $table5 .'.csv"
        INTO TABLE '.$table5.'
        FIELDS TERMINATED by \',\'
        ENCLOSED BY \'"\'
        LINES TERMINATED BY \'\n\'
        IGNORE 1 LINES';
mysqli_query($db, $queryLoad5) or die( mysqli_error($db));


//############################################################## TABLE ESITI CIPE

$table6 = "EsitiCipe";
$queryDelete6 ="DROP TABLE " . $table6;
mysqli_query($db, $queryDelete6) or  mysqli_error($db);

$queryCreate6 = "CREATE TABLE `EsitiCipe` (
  `Codice_EsitoCipe` int(11) DEFAULT NULL,
  `Descrizione_EsitoCipe` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
mysqli_query($db, $queryCreate6) or die( mysqli_error($db));

$queryLoad6 = 'LOAD DATA LOCAL INFILE "'.$pathFiles . $table6 .'.csv"
        INTO TABLE '.$table6.'
        FIELDS TERMINATED by \',\'
        ENCLOSED BY \'"\'
        LINES TERMINATED BY \'\n\'
        IGNORE 1 LINES';
mysqli_query($db, $queryLoad6) or die( mysqli_error($db));



//############################################################## TABLE FIRMATARI

$table7 = "Firmatari";
$queryDelete7 ="DROP TABLE " . $table7;
mysqli_query($db, $queryDelete7) or  mysqli_error($db);

$queryCreate7 = "CREATE TABLE `Firmatari` (
  `Codice_Firmatario` int(11) DEFAULT NULL,
  `Chiave` varchar(255) DEFAULT NULL,
  `Tipo_Firmatario` int(11) DEFAULT NULL,
  `Descrizione_firmatario` varchar(255) DEFAULT NULL,
  `Descrizione_Estesa` varchar(255) DEFAULT NULL,
  `Disattivato_Firmatario` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
mysqli_query($db, $queryCreate7) or die( mysqli_error($db));

$queryLoad7 = 'LOAD DATA LOCAL INFILE "'.$pathFiles . $table7 .'.csv"
        INTO TABLE '.$table7.'
        FIELDS TERMINATED by \',\'
        ENCLOSED BY \'"\'
        LINES TERMINATED BY \'\n\'
        IGNORE 1 LINES';
mysqli_query($db, $queryLoad7) or die( mysqli_error($db));


//############################################################## TABLE LIVELLI

$table8 = "Livelli";
$queryDelete8 ="DROP TABLE " . $table8;
mysqli_query($db, $queryDelete8) or  mysqli_error($db);

$queryCreate8 = "CREATE TABLE `Livelli` (
  `Codice_Livello` int(11) DEFAULT NULL,
  `Descrizione_Livello` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
mysqli_query($db, $queryCreate8) or die( mysqli_error($db));

$queryLoad8 = 'LOAD DATA LOCAL INFILE "'.$pathFiles . $table8 .'.csv"
        INTO TABLE '.$table8.'
        FIELDS TERMINATED by \',\'
        ENCLOSED BY \'"\'
        LINES TERMINATED BY \'\n\'
        IGNORE 1 LINES';
mysqli_query($db, $queryLoad8) or die( mysqli_error($db));


//############################################################## TABLE PRE-CIPE

$table9 = "PreCipe";
$queryDelete9 ="DROP TABLE " . $table9;
mysqli_query($db, $queryDelete9) or  mysqli_error($db);

$queryCreate9 = "CREATE TABLE `PreCipe` (
  `Codice_PreCipe` int(11) DEFAULT NULL,
  `Data_PreCipe` datetime DEFAULT NULL,
  `Progressivo_PreCipe` int(11) DEFAULT NULL,
  `Codice_Titolario` int(11) DEFAULT NULL,
  `Numero_Fascicolo` int(11) DEFAULT NULL,
  `Numero_SottoFascicolo` int(11) DEFAULT NULL,
  `Codice_Registro` int(11) DEFAULT NULL,
  `Codice_Registro2` int(11) DEFAULT NULL,
  `Codice_Registro3` int(11) DEFAULT NULL,
  `Numero_OdgPreCipe` varchar(255) DEFAULT NULL,
  `Progressivo_NumeroPreCipe` int(11) DEFAULT NULL,
  `Codice_ArgomentoPreCipe` int(11) DEFAULT NULL,
  `Oggetto_PreCipe` longtext,
  `Codice_RisultanzaPreCipe` int(11) DEFAULT NULL,
  `Nota_RisultanzaPreCipe` longtext,
  `Codice_Ufficio` int(11) DEFAULT NULL,
  `Numero_Delibera` int(11) DEFAULT NULL,
  `Annotazioni_PreCipe` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
mysqli_query($db, $queryCreate9) or die( mysqli_error($db));

$queryLoad9 = 'LOAD DATA LOCAL INFILE "'.$pathFiles . $table9 .'.csv"
        INTO TABLE '.$table9.'
        FIELDS TERMINATED by \',\'
        ENCLOSED BY \'"\'
        LINES TERMINATED BY \'\n\'
        IGNORE 1 LINES';
mysqli_query($db, $queryLoad9) or die( mysqli_error($db));



//############################################################## TABLE PW UTENTI

$table10 = "PwUtenti";
$queryDelete10 ="DROP TABLE " . $table10;
mysqli_query($db, $queryDelete10) or  mysqli_error($db);

$queryCreate10 = "CREATE TABLE `PwUtenti` (
  `chiave` varchar(255) DEFAULT NULL,
  `Cognome` varchar(255) DEFAULT NULL,
  `Nome` varchar(255) DEFAULT NULL,
  `Codice_Ufficio` int(11) DEFAULT NULL,
  `Codice_Livello` int(11) DEFAULT NULL,
  `Userid` varchar(255) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `PostaElettronica` varchar(255) DEFAULT NULL,
  `CessatoServizio` varchar(255) DEFAULT NULL,
  `Ip` varchar(255) DEFAULT NULL,
  `stazione` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
mysqli_query($db, $queryCreate10) or die( mysqli_error($db));

$queryLoad10 = 'LOAD DATA LOCAL INFILE "'.$pathFiles . $table10 .'.csv"
        INTO TABLE '.$table10.'
        FIELDS TERMINATED by \',\'
        ENCLOSED BY \'"\'
        LINES TERMINATED BY \'\n\'
        IGNORE 1 LINES';
mysqli_query($db, $queryLoad10) or die( mysqli_error($db));


//############################################################## TABLE REGISTRO

$table11 = "Registro";
$queryDelete11 ="DROP TABLE " . $table11;
mysqli_query($db, $queryDelete11) or  mysqli_error($db);

$queryCreate11 = "CREATE TABLE `Registro` (
  `Codice_Registro` int(11) NOT NULL,
  `Progressivo` double DEFAULT NULL,
  `Codice_Titolario` double DEFAULT NULL,
  `Numero_Fascicolo` double DEFAULT NULL,
  `Numero_SottoFascicolo` int(11) DEFAULT NULL,
  `Protocollo_Arrivo` varchar(255) DEFAULT NULL,
  `Data_Arrivo` datetime DEFAULT NULL,
  `Protocollo_Mittente` varchar(255) DEFAULT NULL,
  `Data_Mittente` datetime DEFAULT NULL,
  `Mittente_Registro` varchar(255) DEFAULT NULL,
  `Codice_Amministrazione` int(11) DEFAULT NULL,
  `Oggetto_Registro` longtext,
  `Proposta_Cipe` int(11) DEFAULT NULL,
  `Originale_Registro` int(11) DEFAULT NULL,
  `Originale_Registro_old` varchar(255) DEFAULT NULL,
  `File_Registro` int(11) DEFAULT NULL,
  `File_Registro_old` varchar(255) DEFAULT NULL,
  `Diramazione_Registro` varchar(255) DEFAULT NULL,
  `Nome_Documento` varchar(255) DEFAULT NULL,
  `Annotazioni_Registro` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
mysqli_query($db, $queryCreate11) or die( mysqli_error($db));

$queryLoad11 = 'LOAD DATA LOCAL INFILE "'.$pathFiles . $table11 .'.csv"
        INTO TABLE '.$table11.'
        FIELDS TERMINATED by \',\'
        ENCLOSED BY \'"\'
        LINES TERMINATED BY \'\n\'
        IGNORE 1 LINES';
mysqli_query($db, $queryLoad11) or die( mysqli_error($db));



//############################################################## TABLE REPERTORIO

$table12 = "Repertorio";
$queryDelete12 ="DROP TABLE " . $table12;
mysqli_query($db, $queryDelete12) or  mysqli_error($db);

$queryCreate12 = "CREATE TABLE `Repertorio` (
  `Codice_Repertorio` int(10) DEFAULT NULL,
  `Codice_Titolario` int(10) DEFAULT NULL,
  `Numero_Fascicolo` int(10) DEFAULT NULL,
  `Argomento_Repertorio` varchar(255) DEFAULT NULL,
  `Codice_Amministrazione` varchar(255) DEFAULT NULL,
  `Vecchio_Codice_Ufficio` varchar(255) DEFAULT NULL,
  `Codice_Ufficio` varchar(255) DEFAULT NULL,
  `Codice_Ufficio2` varchar(255) DEFAULT NULL,
  `Codice_Ufficio3` varchar(255) DEFAULT NULL,
  `Codice_Funzionario` varchar(255) DEFAULT NULL,
  `Codice_Funzionario2` varchar(255) DEFAULT NULL,
  `Codice_Funzionario3` varchar(255) DEFAULT NULL,
  `Codice_Commissione` varchar(255) DEFAULT NULL,
  `Data_Magazzino` varchar(255) DEFAULT NULL,
  `Data_Cipe` varchar(255) DEFAULT NULL,
  `Data_Cipe2` varchar(255) DEFAULT NULL,
  `Data_PreCipe` varchar(255) DEFAULT NULL,
  `Data_PreCipe2` varchar(255) DEFAULT NULL,
  `Esito_Cipe` varchar(255) DEFAULT NULL,
  `Specifica_EsitoCipe` varchar(255) DEFAULT NULL,
  `Numero_Delibera` varchar(255) DEFAULT NULL,
  `Anno_Delibera` varchar(255) DEFAULT NULL,
  `Archiviazione_Repertorio` varchar(25) DEFAULT NULL,
  `Annotazioni_Repertorio` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
mysqli_query($db, $queryCreate12) or die( mysqli_error($db));

$queryLoad12 = 'LOAD DATA LOCAL INFILE "'.$pathFiles . $table12 .'.csv"
        INTO TABLE '.$table12.'
        FIELDS TERMINATED by \',\'
        ENCLOSED BY \'"\'
        LINES TERMINATED BY \'\n\'
        IGNORE 1 LINES';
mysqli_query($db, $queryLoad12) or die( mysqli_error($db));


//############################################################## TABLE RISULTANZA PRECIPE

$table13 = "Risultanze_PreCipe";
$queryDelete13 ="DROP TABLE " . $table13;
mysqli_query($db, $queryDelete13) or  mysqli_error($db);

$queryCreate13 = "CREATE TABLE `Risultanze_PreCipe` (
  `Codice_RisultanzaPreCipe` int(11) NOT NULL,
  `Descrizione_RisultanzaPreCipe` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
mysqli_query($db, $queryCreate13) or die( mysqli_error($db));

$queryLoad13 = 'LOAD DATA LOCAL INFILE "'.$pathFiles . $table13 .'.csv"
        INTO TABLE '.$table13.'
        FIELDS TERMINATED by \',\'
        ENCLOSED BY \'"\'
        LINES TERMINATED BY \'\n\'
        IGNORE 1 LINES';
mysqli_query($db, $queryLoad13) or die( mysqli_error($db));


//############################################################## TABLE SEDUTE CIPE

$table14 = "Sedute_Cipe";
$queryDelete14 ="DROP TABLE " . $table14;
mysqli_query($db, $queryDelete14) or  mysqli_error($db);

$queryCreate14 = "CREATE TABLE `Sedute_Cipe` (
  `Codice_SedutaCipe` int(11) DEFAULT NULL,
  `Data_SedutaCipe` datetime DEFAULT NULL,
  `Giorno_SedutaCipe` varchar(255) DEFAULT NULL,
  `Ora_SedutaCipe` varchar(255) DEFAULT NULL,
  `Sede_SedutaCipe` varchar(255) DEFAULT NULL,
  `Codice_Presidente` int(11) DEFAULT NULL,
  `Codice_Segretario` int(11) DEFAULT NULL,
  `Codice_Direttore` int(11) DEFAULT NULL,
  `Ufficiale_SedutaCipe` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
mysqli_query($db, $queryCreate14) or die( mysqli_error($db));

$queryLoad14 = 'LOAD DATA LOCAL INFILE "'.$pathFiles . $table14 .'.csv"
        INTO TABLE '.$table14.'
        FIELDS TERMINATED by \',\'
        ENCLOSED BY \'"\'
        LINES TERMINATED BY \'\n\'
        IGNORE 1 LINES';
mysqli_query($db, $queryLoad14) or die( mysqli_error($db));


//############################################################## TABLE SOTTOFASCICOLI

$table15 = "SottoFascicoli";
$queryDelete15 ="DROP TABLE " . $table15;
mysqli_query($db, $queryDelete15) or  mysqli_error($db);

$queryCreate15 = "CREATE TABLE `SottoFascicoli` (
  `Numero_SottoFascicolo` int(11) DEFAULT NULL,
  `Codice_Titolario` int(11) DEFAULT NULL,
  `Numero_Fascicolo` int(11) DEFAULT NULL,
  `Codice_Progressivo` int(11) DEFAULT NULL,
  `Descrizione_SottoFascicolo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
mysqli_query($db, $queryCreate15) or die( mysqli_error($db));

$queryLoad15 = 'LOAD DATA LOCAL INFILE "'.$pathFiles . $table15 .'.csv"
        INTO TABLE '.$table15.'
        FIELDS TERMINATED by \',\'
        ENCLOSED BY \'"\'
        LINES TERMINATED BY \'\n\'
        IGNORE 1 LINES';
mysqli_query($db, $queryLoad15) or die( mysqli_error($db));


//############################################################## TABLE TIPOARGOMENTICIPE

$table16 = "TipoArgomentiCipe";
$queryDelete16 ="DROP TABLE " . $table16;
mysqli_query($db, $queryDelete16) or  mysqli_error($db);

$queryCreate16 = "CREATE TABLE `TipoArgomentiCipe` (
  `Codice_TipoArgomentoCipe` int(11) DEFAULT NULL,
  `Descrizione_TipoArgomentoCipe` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
mysqli_query($db, $queryCreate16) or die( mysqli_error($db));

$queryLoad16 = 'LOAD DATA LOCAL INFILE "'.$pathFiles . $table16 .'.csv"
        INTO TABLE '.$table16.'
        FIELDS TERMINATED by \',\'
        ENCLOSED BY \'"\'
        LINES TERMINATED BY \'\n\'
        IGNORE 1 LINES';
mysqli_query($db, $queryLoad16) or die( mysqli_error($db));


//############################################################## TABLE TIPOESITICIPE

$table17 = "TipoEsitiCipe";
$queryDelete17 ="DROP TABLE " . $table17;
mysqli_query($db, $queryDelete17) or  mysqli_error($db);

$queryCreate17 = "CREATE TABLE `TipoEsitiCipe` (
  `Codice_TipoEsitoCipe` int(11) DEFAULT NULL,
  `Descrizione_TipoEsitoCipe` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
mysqli_query($db, $queryCreate17) or die( mysqli_error($db));

$queryLoad17 = 'LOAD DATA LOCAL INFILE "'.$pathFiles . $table17 .'.csv"
        INTO TABLE '.$table17.'
        FIELDS TERMINATED by \',\'
        ENCLOSED BY \'"\'
        LINES TERMINATED BY \'\n\'
        IGNORE 1 LINES';
mysqli_query($db, $queryLoad17) or die( mysqli_error($db));


//############################################################## TABLE TIPOFIRMATARI

$table18 = "TipoFirmatari";
$queryDelete18 ="DROP TABLE " . $table18;
mysqli_query($db, $queryDelete18) or  mysqli_error($db);

$queryCreate18 = "CREATE TABLE `TipoFirmatari` (
  `Codice_TipoFirmatario` int(11) DEFAULT NULL,
  `Descrizione_TipoFirmatario` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
mysqli_query($db, $queryCreate18) or die( mysqli_error($db));

$queryLoad18 = 'LOAD DATA LOCAL INFILE "'.$pathFiles . $table18 .'.csv"
        INTO TABLE '.$table18.'
        FIELDS TERMINATED by \',\'
        ENCLOSED BY \'"\'
        LINES TERMINATED BY \'\n\'
        IGNORE 1 LINES';
mysqli_query($db, $queryLoad18) or die( mysqli_error($db));


//############################################################## TABLE TITOLARIO

$table19 = "Titolario";
$queryDelete19 ="DROP TABLE " . $table19;
mysqli_query($db, $queryDelete19) or  mysqli_error($db);

$queryCreate19 = "CREATE TABLE `Titolario` (
  `Codice_Titolario` int(11) DEFAULT NULL,
  `Nome_Titolario` varchar(255) DEFAULT NULL,
  `Descrizione_Titolario` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
mysqli_query($db, $queryCreate19) or die( mysqli_error($db));

$queryLoad19 = 'LOAD DATA LOCAL INFILE "'.$pathFiles . $table19 .'.csv"
        INTO TABLE '.$table19.'
        FIELDS TERMINATED by \',\'
        ENCLOSED BY \'"\'
        LINES TERMINATED BY \'\n\'
        IGNORE 1 LINES';
mysqli_query($db, $queryLoad19) or die( mysqli_error($db));


//############################################################## TABLE UFFICI DIPE

$table20 = "UfficiDipe";
$queryDelete20 ="DROP TABLE " . $table20;
mysqli_query($db, $queryDelete20) or  mysqli_error($db);

$queryCreate20 = "CREATE TABLE `UfficiDipe` (
  `Codice_Ufficio` int(11) DEFAULT NULL,
  `Codice_Direzione` int(11) DEFAULT NULL,
  `Descrizione_Ufficio` varchar(255) DEFAULT NULL,
  `Ordine_Ufficio` int(11) DEFAULT NULL,
  `Disattivo_Ufficio` tinyint(1) DEFAULT NULL,
  `Solo_Delibere` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
mysqli_query($db, $queryCreate20) or die( mysqli_error($db));

$queryLoad20 = 'LOAD DATA LOCAL INFILE "'.$pathFiles . $table20 .'.csv"
        INTO TABLE '.$table20.'
        FIELDS TERMINATED by \',\'
        ENCLOSED BY \'"\'
        LINES TERMINATED BY \'\n\'
        IGNORE 1 LINES';
mysqli_query($db, $queryLoad20) or die( mysqli_error($db));




//############################################################## TABLE ADEMPIMENTI AMBITI

$table21 = "AdempimentiAmbiti";
$queryDelete21 ="DROP TABLE " . $table21;
mysqli_query($db, $queryDelete21) or  mysqli_error($db);

$queryCreate21 = "CREATE TABLE `AdempimentiAmbiti` (
  `id` int(11) DEFAULT NULL,
  `denominazione` varchar(255) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
mysqli_query($db, $queryCreate21) or die( mysqli_error($db));

$queryLoad21 = 'LOAD DATA LOCAL INFILE "'.$pathFiles . $table21 .'.csv"
        INTO TABLE '.$table21.'
        FIELDS TERMINATED by \',\'
        ENCLOSED BY \'"\'
        LINES TERMINATED BY \'\n\'
        IGNORE 1 LINES';
mysqli_query($db, $queryLoad21) or die( mysqli_error($db));

//############################################################## TABLE ADEMPIMENTI AMBITI

$table22 = "AdempimentiTipologie";
$queryDelete22 ="DROP TABLE " . $table22;
mysqli_query($db, $queryDelete22) or  mysqli_error($db);

$queryCreate22 = "CREATE TABLE `AdempimentiTipologie` (
  `id` int(11) DEFAULT NULL,
  `denominazione` varchar(255) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
mysqli_query($db, $queryCreate22) or die( mysqli_error($db));

$queryLoad22 = 'LOAD DATA LOCAL INFILE "'.$pathFiles . $table22 .'.csv"
        INTO TABLE '.$table22.'
        FIELDS TERMINATED by \',\'
        ENCLOSED BY \'"\'
        LINES TERMINATED BY \'\n\'
        IGNORE 1 LINES';
mysqli_query($db, $queryLoad22) or die( mysqli_error($db));


//############################################################## TABLE ADEMPIMENTI AZIONI

$table23 = "AdempimentiAzioni";
$queryDelete23 ="DROP TABLE " . $table23;
mysqli_query($db, $queryDelete23) or  mysqli_error($db);

$queryCreate23 = "CREATE TABLE `AdempimentiAzioni` (
  `id` int(11) DEFAULT NULL,
  `denominazione` varchar(255) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
mysqli_query($db, $queryCreate23) or die( mysqli_error($db));

$queryLoad23 = 'LOAD DATA LOCAL INFILE "'.$pathFiles . $table23 .'.csv"
        INTO TABLE '.$table23.'
        FIELDS TERMINATED by \',\'
        ENCLOSED BY \'"\'
        LINES TERMINATED BY \'\n\'
        IGNORE 1 LINES';
mysqli_query($db, $queryLoad23) or die( mysqli_error($db));


?>