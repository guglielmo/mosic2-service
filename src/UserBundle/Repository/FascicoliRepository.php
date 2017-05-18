<?php

namespace UserBundle\Repository;

use Doctrine\ORM\EntityRepository;
use \UserBundle\Helper\ControllerHelper;

/**
 * FascicoliRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */

class FascicoliRepository extends \Doctrine\ORM\EntityRepository
{
    
    public function totaleFascicoli() {
        $qb = $this->getEntityManager();
        
        $query = $qb
            ->createQueryBuilder()->select('f.id')
            ->from('UserBundle:Fascicoli', 'f')
            ->where('1=1');
            
        return $query->getQuery()->getResult();
    }
    

    public function listaFascicoli($limit, $offset, $sortBy, $sortType, $id_titolari, $numero_fascicolo, $argomento, $id_amministrazione) {
        
        $parameters = array ();
        $filter = "";
        
        if ($id_titolari != "") {
            $filter .= " AND f.idTitolari = :idTitolari ";
            $parameters['idTitolari'] = $id_titolari;
        }
        if ($numero_fascicolo != "") {
            $filter .= " AND f.numeroFascicolo = :numeroFascicolo ";
            $parameters['numeroFascicolo'] = $numero_fascicolo;
        }
        if ($argomento != "") {
            $filter .= " AND f.argomento LIKE :argomento ";
            $parameters['argomento'] = '%'.$argomento.'%';
        }
        if ($id_amministrazione != "") {
            $filter .= " AND f.idAmministrazione = :idAmministrazione ";
            $parameters['idAmministrazione'] = $id_amministrazione;
        }
        
        
        $qb = $this->getEntityManager();
				// ->leftJoin('UserBundle:RelAmministrazioniFascicoli', 'r', 'WITH', 'f.id = r.idFascicoli')
        // ->createQueryBuilder()->select('f,r')

        $query = $qb
            ->createQueryBuilder()->select('f.id as id,
                                            f.codiceRepertorio as codice_repertorio,
                                            f.idTitolari as id_titolari,
                                            f.numeroFascicolo as numero_fascicolo,
                                            f.argomento as argomento,
                                            f.idAmministrazione as id_amministrazione,
                                            f.dataMagazzino as data_magazzino,
                                            f.dataCipe as data_cipe,
                                            f.dataCipe2 as data_cipe2,
                                            f.archiviazioneRepertorio as archiviazione_repertorio,
                                            f.annotazioni as annotazioni,
                                            f.idNumeriDelibera as id_numeri_delibera,
                                            f.idEsitiCipe as id_esiti_cipe,
                                            f.idArchivioRepertorio as id_archivio_repertorio,
                                            r.idAmministrazioni as id_amministrazioni'
																						)
            ->from('UserBundle:Fascicoli', 'f')
            ->leftJoin('UserBundle:RelAmministrazioniFascicoli', 'r', 'WITH', 'f.id = r.idFascicoli')
            ->where('1=1' . $filter)
            ->setParameters($parameters)
            ->setFirstResult( $offset )
            ->setMaxResults( $limit )
            ->orderBy($sortBy, $sortType);
            
            
        //print_r($query->getDql());
        //print_r($query->getQuery()->getSql());

        return $query->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_SCALAR);
        //return $query->getQuery()->getResult();
        
    }
		
		
		
		
    public function schedaFascicolo($id) {

        $parameters = array ();
        $filter = "";
        $filter .= " AND f.id = :id ";
        $parameters['id'] = $id;

        $qb = $this->getEntityManager();

        $query = $qb
            ->createQueryBuilder()->select('f.id as id,
                                            f.codiceRepertorio as codice_repertorio,
                                            f.idTitolari as id_titolari,
                                            f.numeroFascicolo as numero_fascicolo,
                                            f.argomento as argomento,
                                            f.idAmministrazione as id_amministrazione,
                                            f.dataMagazzino as data_magazzino,
                                            f.dataCipe as data_cipe,
                                            f.dataCipe2 as data_cipe2,
                                            f.archiviazioneRepertorio as archiviazione_repertorio,
                                            f.annotazioni as annotazioni,
                                            f.idNumeriDelibera as id_numeri_delibera,
                                            f.idEsitiCipe as id_esiti_cipe,
                                            f.idArchivioRepertorio as id_archivio_repertorio,
                                            r.idAmministrazioni as id_amministrazioni,
                                            t.idTags as id_tags
                                            ')
            ->from('UserBundle:Fascicoli', 'f')
            ->leftJoin('UserBundle:RelAmministrazioniFascicoli', 'r', 'WITH', 'f.id = r.idFascicoli')
            ->leftJoin('UserBundle:RelTagsFascicoli', 't', 'WITH', 'f.id = t.idFascicoli')
            ->where('1=1' . $filter)
            ->setParameters($parameters);
            
            
        //print_r($query->getDql());
        //print_r($query->getQuery()->getSql());

        return $query->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_SCALAR);
    }

    
}
