<?php
/**
 * Created by PhpStorm.
 * User: Bruno
 * Date: 07/05/2017
 * Time: 11:46
 */

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Commentaire;
use AppBundle\Entity\Episode;
use AppBundle\Form\CommentaireType;
use AppBundle\Form\EpisodeType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class EpisodeController extends Controller
{
    /**
     * @Route("/episode/{id}", name="episode")
     */
    public function affichageAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $episode = $em->getRepository('AppBundle:Episode')->find($id);

        if (null === $episode)
        {
            throw new NotFoundHttpException("L'épisode n'existe pas.");
        }

        $commentairesBase = $this->getDoctrine()->getManager()->getRepository('AppBundle:Commentaire')->findCommentaireBase($id);
        $commentaireNew = new  Commentaire();
        $form = $this->get('form.factory')->create(CommentaireType::class, $commentaireNew);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid())
        {
            $idUser = $this->getUser()->getId();
            $userRecup = $this->getDoctrine()->getManager()->getRepository('AppBundle:User')->find($idUser);
            $commentaireNew->setEpisode($episode);
            $commentaireNew->setUser($userRecup);

            if($commentaireNew->recupIdCommentaire != NULL)
            {
                $comm = $this->getDoctrine()->getManager()->getRepository('AppBundle:Commentaire')->find($commentaireNew->recupIdCommentaire);
                $commentaireNew->setParent($comm);
            }

            $em->persist($commentaireNew);
            $em->flush();
            $request->getSession()->getFlashBag()->add('info', 'Votre commentaire a bien été ajouté.');

            return $this->redirectToRoute('episode', array('id' => $episode->getId()));
        }

        return $this->render('blog/episode.html.twig', array(
            'episode' => $episode,
            'commentaires' => $commentairesBase,
            'form' => $form->createView()));
    }

    /**
     * @Route("/ecriture-episode", name="ecriture_episode")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function ecritureAction(Request $request)//Pour l'écriture d'un épisode
    {
        $episode = new Episode();
        $form = $this->get('form.factory')->create(EpisodeType::class, $episode);

        if ($request->get('submitAction') == 'Publier')
        {
            $this->gestionEpisode($request,$form,$episode,true, 'publié');
            return $this->redirectToRoute('admin');
        }
        elseif ($request->get('submitAction') == 'Enregistrer')
        {
            $bool = $episode->getPublier();
            $this->gestionEpisode($request,$form,$episode, $bool, 'enregistré');
            return $this->redirectToRoute('admin');
        }

        return $this->render('Account/ecriture_article.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/supprimer-episode/{id}", name="supprimer_episode")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $episode = $em->getRepository('AppBundle:Episode')->find($id);
        $commentaires = $em->getRepository('AppBundle:Commentaire')->findBy(array('episode' => $episode));

        foreach ($commentaires as $commentaire)
        {
            $em->remove($commentaire);
        }

        $em->remove($episode);
        $em->flush();
        $request->getSession()->getFlashBag()->add('info', 'Episode supprimée.');

        return $this->redirectToRoute('admin');
    }


    /**
     * @Route("/editer-episode/{id}", name="editer_episode")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $episode = $em->getRepository('AppBundle:Episode')->find($id);
        $form = $this->get('form.factory')->create(EpisodeType::class, $episode);

        if ($request->get('submitAction') == 'Publier')
        {
            $this->gestionEpisode($request,$form,$episode,true, 'publié');
            return $this->redirectToRoute('admin');
        }

        if ($request->get('submitAction') == 'Depublier')
        {
            $this->gestionEpisode($request,$form,$episode,false, 'dépublié');
            return $this->redirectToRoute('admin');
        }

        if ($request->get('submitAction') == 'Enregistrer')
        {
            $bool = $episode->getPublier();
            $this->gestionEpisode($request,$form,$episode, $bool, 'enregistré');
            return $this->redirectToRoute('admin');
        }

        return $this->render('Account/edition_article.html.twig', array('form' => $form->createView()));
    }

    private function gestionEpisode(Request $request,$form,$episode,$bool, $message)
    {
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid())
        {
            $episode->setPublier($bool);
            $em = $this->getDoctrine()->getManager();
            $em->persist($episode);
            $em->flush();
            $session = $request->getSession();
            //Affichage du message flash pour la validation du nouvel épisode
            $session->getFlashBag()->add('info', 'Votre épisode a bien été '.$message);
        }
    }

}