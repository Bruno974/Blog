<?php
/**
 * Created by PhpStorm.
 * User: Bruno
 * Date: 10/05/2017
 * Time: 10:24
 */

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Commentaire;
use Symfony\Component\HttpFoundation\Request;

class CommentaireController extends Controller
{
    /**
     * @Route("/enlever-signaler/{id}", name="enlever_signaler")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function enleverSignalerAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $commentaire = $em->getRepository('AppBundle:Commentaire')->find($id);
        $commentaire->setSignaler(false);
        $em->persist($commentaire);
        $em->flush();
        $request->getSession()->getFlashBag()->add('info', 'Le commentaire n\'est plus signalé.');

        return $this->redirectToRoute('admin');
    }

    /**
     * @Route("/supprimer-commentaire/{id}", name="supprimer_commentaire")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteCommentaireAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $commentaire = $em->getRepository('AppBundle:Commentaire')->find($id);
        $em->remove($commentaire);
        $em->flush();
        $request->getSession()->getFlashBag()->add('info', 'Commentaire supprimé.');
        return $this->redirectToRoute('admin');
    }

    /**
     * @Route("/signaler-commentaire/{id}", name="signaler_commentaire")
     */
    public function signalerAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $commentaire = $em->getRepository('AppBundle:Commentaire')->find($id);
        $commentaire->setSignaler(true);
        $episode = $commentaire->getEpisode();
        $idEpisode = $episode->getId();
        $em->persist($commentaire);
        $em->flush();
        $request->getSession()->getFlashBag()->add('info', 'Votre commentaire a bien été signalé.');
        return $this->redirectToRoute('episode', array('id' => $idEpisode))   ;
    }

   /* public function test($id)
    {
        $em = $this->getDoctrine()->getManager();
        $commentaireParent = $em->getRepository('AppBundle:Commentaire')->find($id);
        $commentaireEnfant = new Commentaire();
        $commentaireEnfant->addChild($commentaireParent);
        $em->persist($commentaireEnfant);
        $em->flush();

    }*/
}