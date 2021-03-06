<?php

namespace UserBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * AdempimentiRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */

class AdempimentiRepository extends \Doctrine\ORM\EntityRepository
{

    public function listaAdempimenti($limit, $offset, $sortBy, $sortType) {
		
		$qb = $this->getEntityManager();

        $query = $qb
            ->createQueryBuilder()->select('t')
            ->from('UserBundle:Adempimenti', 't')
            ->where('1=1')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->orderBy('t.'.$sortBy, $sortType);
            
            
        //print_r($query->getDql());
        //print_r($query->getQuery()->getSql());

        return $query->getQuery()->getResult();
        
    }

}