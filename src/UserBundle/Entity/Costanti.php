<?php

namespace UserBundle\Entity;

class Costanti {

    const PATH_IN_DATABASE = "service/";
    const PATH_IN_SERVER = "";

    const URL_ALLEGATI_REGISTRI = Costanti::PATH_IN_DATABASE . "files/REGISTRO_MOSIC";
    const URL_ALLEGATI_PRECIPE = Costanti::PATH_IN_DATABASE . "files/RIUNIONI_PRECIPE";
    const URL_ALLEGATI_DELIBERE = Costanti::PATH_IN_DATABASE . "files/DELIBERE";

}
