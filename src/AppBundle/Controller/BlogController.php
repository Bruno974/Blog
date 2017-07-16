<?php
/**
 * Created by PhpStorm.
 * User: Bruno
 * Date: 03/05/2017
 * Time: 15:13
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BlogController extends Controller
{
    //Fixe le nbre d'épisodes à 5 par page
    const NBRE_PAR_PAGE = 5;

    /**
     *@Route("/blog/{page}", name="home_page")
     */
    public function blogAction($page=1)
    {
        //pas de page 0 et négative
        if ($page < 1) {
            throw $this->createNotFoundException("La page " . $page . " n'existe pas.");
        }

        //On récupère l'objet Paginator
        $episodes = $this->getDoctrine()->getManager()->getRepository('AppBundle:Episode')->findAllEpisodes($page, self::NBRE_PAR_PAGE);

        //On calcul le nombre total de pages grâce au count($episode) qui retourne le nombre total d'annonces
        $nbPages = ceil(count($episodes) / self::NBRE_PAR_PAGE);

        //Si la page n'existe pas, on retourne une erreur
        if ($page > $nbPages) {
            throw $this->createNotFoundException("La page " . $page . " n'existe pas.");
        }

        return $this->render('blog/index.html.twig', array(
            'episodes' => $episodes,
            'nbPages' => $nbPages,
            'page' => $page,));
    }
}