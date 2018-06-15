<?php

namespace UserBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\Bundle\DoctrineBundle\DependencyInjection\DoctrineExtension;
/**
 * DelibereRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */

class DelibereRepository extends \Doctrine\ORM\EntityRepository
{

    public function listaDelibere($limit, $offset, $sortBy, $sortType, $argomento) {
        $parameters = array ();
        $filter = "";

        if ($argomento != "") {
            $filter .= " AND d.argomento LIKE :argomento ";
            $parameters['argomento'] = '%'.$argomento.'%';
        }

        $qb = $this->getEntityManager();

        $query = $qb
            ->createQueryBuilder()->select('d.id as id,
                                            UNIX_TIMESTAMP(d.data) * 1000 as data,
                                            d.idStato as id_stato,
                                            d.numero as numero,
                                            d.argomento as argomento,
                                            d.finanziamento as finanziamento,
                                            d.note as note,
                                            d.noteServizio as note_servizio,

                                            d.foglioCC as foglio_cc,
                                            d.numeroCC as numero_cc,
                                            d.idRegistroCC as registro_cc,
                                            d.numeroGU as numero_gu,
                                            UNIX_TIMESTAMP(d.dataDirettoreInvio) * 1000 as data_direttore_invio,
                                            UNIX_TIMESTAMP(d.dataDirettoreRitorno) * 1000 as data_direttore_ritorno,
                                            UNIX_TIMESTAMP(d.dataMefInvio) * 1000 as data_mef_invio,
                                            UNIX_TIMESTAMP(d.dataMefPec) * 1000 as data_mef_pec,
                                            UNIX_TIMESTAMP(d.dataMefRitorno) * 1000 as data_mef_ritorno,
                                            
                                            d.noteDirettore as note_direttore,
                                            d.noteMef as note_mef,
                                            d.noteSegretario as note_segretario,
                                            d.notePresidente as note_presidente,
                                            d.noteCC as note_cc,
                                            d.noteP as note_p,
                                            d.noteGU as note_gu,

                                            UNIX_TIMESTAMP(d.dataConsegna) * 1000 as data_consegna,
                                            UNIX_TIMESTAMP(d.dataSegretarioInvio) * 1000 as data_segretario_invio,
                                            UNIX_TIMESTAMP(d.dataSegretarioRitorno) * 1000 as data_segretario_ritorno,
                                            UNIX_TIMESTAMP(d.dataPresidenteInvio) * 1000 as data_presidente_invio,
                                            UNIX_TIMESTAMP(d.dataPresidenteRitorno) * 1000 as data_presidente_ritorno,
                                            UNIX_TIMESTAMP(d.dataInvioCC) * 1000 as data_invio_cc,
                                            UNIX_TIMESTAMP(d.dataRegistrazioneCC) * 1000 as data_registrazione_cc,
                                            UNIX_TIMESTAMP(d.dataInvioGU) * 1000 as data_invio_gu,
                                            UNIX_TIMESTAMP(d.dataGU) * 1000 as data_gu,                                       
                                            d.situazione as situazione,                                       
                                            
                                            ud.idUffici as id_uffici,
                                            td.idTags as id_tags')
            ->from('UserBundle:Delibere', 'd')
            ->leftJoin('UserBundle:RelUfficiDelibere', 'ud', 'WITH', 'd.id = ud.idDelibere')
            ->leftJoin('UserBundle:RelTagsDelibere', 'td', 'WITH', 'd.id = td.idDelibere')
            ->where('1=1')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->addOrderBy('d.data', "DESC")
            ->addOrderBy('d.'.$sortBy, $sortType);

            
        //print_r($query->getDql());
        //print_r($query->getQuery()->getSql());exit;

        return $query->getQuery()->getResult();
    }



    public function schedaDelibera($id) {

        $parameters = array ();
        $filter = "";
        $filter .= " AND d.id = :id ";
        $parameters['id'] = $id;

        $qb = $this->getEntityManager();

        $query = $qb
            ->createQueryBuilder()->select('d.id as id,
                                            d.data as data,
                                            d.numero as numero,
                                            d.idStato as id_stato,
                                            d.argomento as argomento,
                                            d.finanziamento as finanziamento,
                                            d.note as note,
                                            d.noteServizio as note_servizio,
                                            d.scheda as scheda,
                                            d.dataConsegna as data_consegna,
                                            d.noteFirma as note_firma,
                                            d.idDirettore as id_direttore,
                                            d.dataDirettoreInvio as data_direttore_invio,
                                            d.dataDirettoreRitorno as data_direttore_ritorno,
                                            d.noteDirettore as note_direttore,
                                            d.invioMef as invio_mef,
                                            d.dataMefInvio as data_mef_invio,
                                            d.dataMefPec as data_mef_pec,
                                            d.dataMefRitorno as data_mef_ritorno,
                                            d.noteMef as note_mef,
                                            d.idSegretario as id_segretario,
                                            d.dataSegretarioInvio as data_segretario_invio,
                                            d.dataSegretarioRitorno as data_segretario_ritorno,
                                            d.noteSegretario as note_segretario,
                                            d.idPresidente as id_presidente,
                                            d.dataPresidenteInvio as data_presidente_invio,
                                            d.dataPresidenteRitorno as data_presidente_ritorno,
                                            d.notePresidente as note_presidente,
                                            d.dataInvioCC as data_invio_cc,
                                            d.numeroCC as numero_cc,
                                            d.dataRegistrazioneCC as data_registrazione_cc,
                                            d.idRegistroCC as id_registro_cc,
                                            d.foglioCC as foglio_cc,
                                            d.tipoRegistrazioneCC as tipo_registrazione_cc,
                                            d.noteCC as note_cc,
                                            d.dataInvioP as data_invio_p,
                                            d.noteP as note_p,
                                            d.dataInvioGU as data_invio_gu,
                                            d.numeroInvioGU as numero_invio_gu,
                                            d.tipoGU as tipo_gu,
                                            d.dataGU as data_gu,
                                            d.numeroGU as numero_gu,
                                            d.dataEcGU as data_ec_gu,
                                            d.numeroEcGU as numero_ec_gu,
                                            d.dataCoGU as data_co_gu,
                                            d.numeroCoGU as numero_co_gu,
                                            d.pubblicazioneGU as pubblicazione_gu,
                                            d.noteGU as note_gu,
                                            d.numero as numero_delibera,

                                            ud.idUffici as id_uffici,
                                            fd.idFirmatari as id_segretariato,
                                            td.idTags as id_tags,
                                            df.idFascicoli as id_fascicoli
                                            ')
            ->from('UserBundle:Delibere', 'd')
            ->leftJoin('UserBundle:RelUfficiDelibere', 'ud', 'WITH', 'd.id = ud.idDelibere')
            ->leftJoin('UserBundle:RelFirmatariDelibere', 'fd', 'WITH', 'd.id = fd.idDelibere')
            ->leftJoin('UserBundle:RelTagsDelibere', 'td', 'WITH', 'd.id = td.idDelibere')
            ->leftJoin('UserBundle:RelDelibereFascicoli', 'df', 'WITH', 'd.id = df.idDelibere')
            ->where('1=1' . $filter)
            ->setParameters($parameters);


        //print_r($query->getDql());
        //print_r($query->getQuery()->getSql());

        return $query->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_SCALAR);
    }


    public function cercaDataDelibera($data, $numero) {

        $parameters = array ();
        $filter = "";
        $filter .= " AND UNIX_TIMESTAMP(c.data) * 1000 = :data ";
        $filter .= " AND co.numeroDelibera = :numero_delibera ";
        $parameters['data'] = $data;
        $parameters['numero_delibera'] = $numero;

        $qb = $this->getEntityManager();

        $query = $qb
            ->createQueryBuilder()->select('co.idFascicoli as id_fascicoli,
                                            co.numeroDelibera as numero_delibera,
                                            co.id as id,
                                            co.idCipe as id_cipe,
                                            co.ordine as ordine,
                                            roc.idRegistri as id_registri'
                                          )
            ->from('UserBundle:Cipe', 'c')
            ->leftJoin('UserBundle:CipeOdg', 'co', 'WITH', 'c.id = co.idCipe')
            ->leftJoin('UserBundle:RelRegistriOdgCipe', 'roc', 'WITH', 'roc.idOdgCipe = co.id')
            ->where('1=1' . $filter)
            ->setParameters($parameters);


        //print_r($query->getDql());
        //print_r($query->getQuery()->getSql());

        return $query->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_SCALAR);
    }





    public function getAllegatiByIdDelibere($id) {
        $parameters = array ();
        $filter = "";
        $filter .= " AND p.idDelibere = :id ";
        $parameters['id'] = $id;

        $qb = $this->getEntityManager();

        $query = $qb
            ->createQueryBuilder()->select('a.id as id,
                                            a.data as data,
                                            a.file as file,
                                            p.idAllegati as id_allegati,
                                            p.idDelibere as id_delibere,
                                            p.tipo as tipo
                                            ')
            ->from('UserBundle:Allegati', 'a')
            ->leftJoin('UserBundle:RelAllegatiDelibere', 'p', 'WITH', 'a.id = p.idAllegati')
            ->where('1=1' . $filter)
            ->setParameters($parameters);

        //print_r($query->getDql());
        //print_r($query->getQuery()->getSql());

        //return $query->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_SCALAR);
        $array = array ();
        foreach ($query->getQuery()->getResult() as $item) {
            $path_parts = pathinfo($item['file']);

            $array[] = array(
                'id' => $item['id'],
                'data' => filemtime($item['file']) * 1000,
                'nome' => $path_parts['basename'],
                'tipo' => $path_parts['extension'],
                'relURI' => $item['file'],
                'dimensione' => filesize($item['file']),
                'tipologia' => $item['tipo']
            );
        }



        return $array;



    }


    public function getDelibereByYear($year = false) {
        $parameters = array ();
        $filter = "";
        if ($year != false && $year != "all") {
            $filter .= " AND d.data > :from AND d.data <= :to ";
            $parameters['from'] = $year."-01-01";
            $parameters['to'] = $year."-12-31";
        }

        $qb = $this->getEntityManager();

        $query = $qb
            ->createQueryBuilder()->select('d.id as id,
                                            d.data as data,
                                            d.dataConsegna as data_consegna,
                                            d.numero as numero,
                                            d.dataDirettoreInvio as data_direttore_invio,
                                            d.dataDirettoreRitorno as data_direttore_ritorno,
                                            d.dataMefInvio as data_mef_invio,
                                            d.dataMefPec as data_mef_pec,
                                            d.dataMefRitorno as data_mef_ritorno,
                                            d.dataSegretarioInvio as data_segretario_invio,
                                            d.dataSegretarioRitorno as data_segretario_ritorno,
                                            d.dataPresidenteInvio as data_presidente_invio,
                                            d.dataPresidenteRitorno as data_presidente_ritorno,
                                            d.dataInvioCC as data_invio_cc,
                                            d.tipoRegistrazioneCC as tipo_registrazione_cc,
                                            d.dataRegistrazioneCC as data_registrazione_cc,
                                            d.dataInvioGU as data_invio_gu,
                                            d.dataGU as data_gu
                                            ')
            ->from('UserBundle:Delibere', 'd')
            ->where('1=1' . $filter)
            ->setParameters($parameters)
            ->orderBy('d.data', "ASC");

        //print_r($query->getDql());
        //print_r($query->getQuery()->getSql());

        return $query->getQuery()->getResult();

    }


    public function getDelibereByData($data = false) {
        $parameters = array ();
        $filter = "";
        if ($data != false && $data != "all") {
            $filter .= " AND d.data = :data ";
            $parameters['data'] = $data;
        }

        $qb = $this->getEntityManager();

        $query = $qb
            ->createQueryBuilder()->select('d.id as id,
                                            d.data as data,
                                            d.dataConsegna as data_consegna,
                                            d.numero as numero,
                                            d.dataDirettoreInvio as data_direttore_invio,
                                            d.dataDirettoreRitorno as data_direttore_ritorno,
                                            d.dataMefInvio as data_mef_invio,
                                            d.dataMefPec as data_mef_pec,
                                            d.dataMefRitorno as data_mef_ritorno,
                                            d.dataSegretarioInvio as data_segretario_invio,
                                            d.dataSegretarioRitorno as data_segretario_ritorno,
                                            d.dataPresidenteInvio as data_presidente_invio,
                                            d.dataPresidenteRitorno as data_presidente_ritorno,
                                            d.dataInvioCC as data_invio_cc,
                                            d.dataRegistrazioneCC as data_registrazione_cc,
                                            d.dataInvioGU as data_invio_gu,
                                            d.dataGU as data_gu
                                            ')
            ->from('UserBundle:Delibere', 'd')
            ->where('1=1' . $filter)
            ->setParameters($parameters)
            ->orderBy('d.numero', "DESC");

        //print_r($query->getDql());
        //print_r($query->getQuery()->getSql());

        return $query->getQuery()->getResult();

    }



    public function getAllegatiCCRByIdCCR($id) {
        $parameters = array ();
        $filter = "";
        $filter .= " AND rar.idDelibereCCR = :id ";
        $parameters['id'] = $id;

        $qb = $this->getEntityManager();

        $query = $qb
            ->createQueryBuilder()->select('a.id as id,
                                            a.data as data,
                                            a.file as file,
                                            rar.idAllegati as id_allegati,
                                            rar.idDelibereCCR as id_delibere_ccr
                                            ')
            ->from('UserBundle:Allegati', 'a')
            ->leftJoin('UserBundle:RelAllegatiDelibereCCR', 'rar', 'WITH', 'a.id = rar.idAllegati')
            ->where('1=1' . $filter)
            ->setParameters($parameters);

        //print_r($query->getDql());
        //print_r($query->getQuery()->getSql());

        $array = array ();
        foreach ($query->getQuery()->getResult() as $item) {
            $path_parts = pathinfo($item['file']);

            $array[] = array(
                'id' => $item['id'],
                'data' => filemtime($item['file']) * 1000,
                'nome' => $path_parts['basename'],
                'tipo' => $path_parts['extension'],
                'relURI' => $item['file'],
                'dimensione' => filesize($item['file'])
            );
        }



        return $array;
    }

}