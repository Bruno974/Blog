<?php
/**
 * Created by PhpStorm.
 * User: Bruno
 * Date: 19/05/2017
 * Time: 16:16
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * @Route("/supprimmer_utilisateur/{id}", name="supprimer_utilisateur")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function deleteAction($id, Request $request) //Supprimmer un utilisateur
    {
        $em = $this->getDoctrine()->getManager();
        $utilisateur = $em->getRepository('AppBundle:User')->find($id);
        $em->remove($utilisateur);
        $em->flush();
        $request->getSession()->getFlashBag()->add('info', 'Utilisateur supprimé.');

        return $this->redirectToRoute('admin');
    }
}