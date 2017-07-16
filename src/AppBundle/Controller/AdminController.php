<?php
/**
 * Created by PhpStorm.
 * User: Bruno
 * Date: 07/05/2017
 * Time: 13:46
 */

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class AdminController extends Controller
{
    /**
     * @Route("/admin", name="admin")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function adminAction()
    {
        $em = $this->getDoctrine()->getManager();
        $episodes = $em->getRepository('AppBundle:Episode')->listEpisodesAdmin();
        $commentaires = $this->getDoctrine()->getManager()->getRepository('AppBundle:Commentaire')->findCommentaireAdmin();
        $utilisateurs = $em->getRepository('AppBundle:User')->findAll();

        return $this->render('Account/admin.html.twig', array(
            'episodes' => $episodes,
            'commentaires' => $commentaires,
            'utilisateurs' => $utilisateurs));
    }
}