<?php

namespace UserBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * PreCipeOdgRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */


class PreCipeOdgRepository extends \Doctrine\ORM\EntityRepository
{

    public function listaPrecipeOdg($limit, $offset, $sortBy, $sortType) {
        $parameters = array ();
        $filter = "";

        $qb = $this->getEntityManager();
        $query = $qb
            ->createQueryBuilder()->select('p')
            ->from('UserBundle:PreCipeOdg', 'p')
            ->where('1=1' . $filter)
            ->setParameters($parameters)
            ->setFirstResult( $offset )
            ->setMaxResults( $limit )
            ->orderBy('p.'.$sortBy, $sortType);


        //print_r($query->getDql());

        return $query->getQuery()->getResult();
    }
	
	  
}
