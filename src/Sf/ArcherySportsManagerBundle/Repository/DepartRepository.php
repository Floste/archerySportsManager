<?php

namespace Sf\ArcherySportsManagerBundle\Repository;
use Doctrine\ORM\EntityRepository;
use Sf\ArcherySportsManagerBundle\Entity\Archer;
use Sf\ArcherySportsManagerBundle\Entity\Saison;

/**
 * DepartRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DepartRepository extends EntityRepository
{
    public function getNbDepartsForSaison(Saison $saison){
        return $this->createQueryBuilder('d')
            ->select('count(d.id)')
            ->join('d.concours','c')
            ->andWhere('c.saison = :saison')
            ->setParameter('saison',$saison->getId())
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    public function getNbCompetiteursForSaison(Saison $saison){
        return $this->createQueryBuilder('d')
            ->select('count(distinct a.id)')
            ->join('d.concours','c')
            ->join('d.archer','a')
            ->andWhere('c.saison = :saison')
            ->setParameter('saison',$saison->getId())
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }
    public function getDepartsForArcherSaisonDiscipline(Archer $archer, Saison $saison, $discipline){
        return $this->createQueryBuilder('d')
            ->join('d.concours','c')
            ->join('d.resultat','r')
            ->andWhere('c.saison = :saison')
            ->setParameter('saison',$saison->getId())
            ->andWhere('d.discipline = :discipline')
            ->setParameter('discipline',$discipline)
            ->andWhere('d.archer = :archer')
            ->setParameter('archer',$archer->getId())
            ->getQuery()
            ->getResult()
            ;
    }

    public function getDepartsForArcherSaison(Archer $archer, Saison $saison){
        return $this->createQueryBuilder('d')
            ->join('d.concours','c')
            ->join('d.resultat','r')
            ->andWhere('c.saison = :saison')
            ->setParameter('saison',$saison->getId())
            ->andWhere('d.archer = :archer')
            ->setParameter('archer',$archer->getId())
            ->getQuery()
            ->getResult()
            ;
    }

}
