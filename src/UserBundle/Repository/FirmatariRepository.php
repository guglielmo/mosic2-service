<?php

namespace UserBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\DBAL\DriverManager;

/**
 * FirmatariRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */

class FirmatariRepository extends \Doctrine\ORM\EntityRepository
{

    public function listaFirmatari($limit, $offset, $sortBy, $sortType) {
    
        $qb = $this->getEntityManager();

        $query = $qb
            ->createQueryBuilder()->select('t')
            ->from('UserBundle:Firmatari', 't')
            ->where('1=1')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->orderBy('t.'.$sortBy, $sortType);
            
            
        //print_r($query->getDql());
        //print_r($query->getQuery()->getSql());

        return $query->getQuery()->getResult();

        
        
        
        
        //return $this->getEntityManager()
        //        ->createQuery('SELECT t
        //                        FROM UserBundle:Firmatari t
        //                        WHERE 1=1')->setFirstResult($offset)
        //                                   ->setMaxResults($limit)
        //                                   ->getResult();
    }



}