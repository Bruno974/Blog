<?php

namespace AppBundle\Repository;

use Doctrine\ORM\Tools\Pagination\Paginator;

class EpisodeRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllEpisodes($page, $nbreParPage)
    {
        $query = $this->createQueryBuilder('e')
            ->where('e.publier = 1')
            ->orderBy('e.id', 'DESC')
            ->getQuery()
            //On définit l'épisode à partir de laquelle commencer la liste
            ->setFirstResult(($page-1) * $nbreParPage)
            //Le nombre d'épisode à afficher sur une page
            ->setMaxResults($nbreParPage);

        return new Paginator($query, true);
    }

    public function listEpisodesAdmin()
    {
        $query = $this->createQueryBuilder('e')
            ->addOrderBy('e.publier')
            ->addOrderBy('e.id', 'DESC')
            ->getQuery()->getResult();

        return $query;
    }
}
