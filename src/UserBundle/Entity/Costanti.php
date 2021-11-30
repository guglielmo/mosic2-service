<?php

namespace UserBundle\Entity;

class Costanti {

    //const PATH_IN_DATABASE = "service/";
    const PATH_IN_DATABASE = "";

    const PATH_IN_SERVER = "";

    const PATH_PHP = "/usr/bin/php";
    //const PATH_PHP = "/opt/php-5.6.25/bin/php";

    const URL_ALLEGATI_REGISTRI = Costanti::PATH_IN_DATABASE . "files/REGISTRO MOSIC";
    const URL_ALLEGATI_PRECIPE = Costanti::PATH_IN_DATABASE . "files/RIUNIONI_PRECIPE";
    const URL_ALLEGATI_CIPE = Costanti::PATH_IN_DATABASE . "files/SEDUTE_CIPE";
    const URL_ALLEGATI_DELIBERE = Costanti::PATH_IN_DATABASE . "files/DELIBERE";


}
