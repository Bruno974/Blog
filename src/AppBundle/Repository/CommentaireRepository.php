<?php
/**
 * Created by PhpStorm.
 * User: Bruno
 * Date: 03/05/2017
 * Time: 22:10
 */

namespace AppBundle\Repository;


class CommentaireRepository extends \Doctrine\ORM\EntityRepository
{
    public function findCommentaireBase($id) //récupére les commentaires de base
    {
        //Récupère tous les commentaires sans les réponses parent_id = 0
        return $this->createQueryBuilder('c')
                    ->where('c.episode = :id')->setParameter('id', $id)
                    ->andWhere('c.parent IS NULL')
                    ->getQuery()->getResult();
    }

    public function findCommentaireUtilisateur($id)
    {
        return $this->createQueryBuilder('c')
            ->where('c.user = :id')->setParameter('id', $id)
            ->getQuery()->getResult();
    }
   /* public function findCommentaireReponse($id) //Récupére les réponses des commentaires
    {
        //Récupère tous les  les réponses parent_id != 0
        $qb = $this->createQueryBuilder('c');
        $qb
            ->where('c.episode = :id')->setParameter('id', $id)
            ->andWhere('c.parent_id != 0');
        return $qb->getQuery()->getResult();

    }*/

    public function findCommentaireAdmin()//Récupérer les commentaires avec ceux signaler en premier et décroissant
    {
        $queryBuilder = $this->createQueryBuilder('c');
        $queryBuilder->orderBy('c.signaler', 'DESC');
        $query = $queryBuilder->getQuery()->getResult();
        return $query;
    }

}