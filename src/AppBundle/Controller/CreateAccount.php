<?php
/**
 * Created by PhpStorm.
 * User: Bruno
 * Date: 06/05/2017
 * Time: 15:32
 */

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Form\UserType;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class CreateAccount extends Controller
{
    /**
     * @Route("/create-account", name="create_account")
     */
    public function createAccountAction(Request $request)
    {
        $utilisateur = new User();
        $form = $this->get('form.factory')->create(UserType::class, $utilisateur);
        $form->setData( $utilisateur->setRoles(array('ROLE_USER'))); //Initialiser le champs role sur user
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid())
        {
            //Encoder le password
            $password = $this->get('security.password_encoder')
                ->encodePassword($utilisateur, $utilisateur->getPassword());
            $utilisateur->setPassword($password);

            $utilisateur->setRoles(array('ROLE_USER')); //sécurité pour hydrater l'inscription sur user a mettre ds le constructeur

            //Sauvegarder l'utilisateur
            $em = $this->getDoctrine()->getManager();
            $em->persist($utilisateur);
            $em->flush();

            //Creation du token pour le login automatique apres inscription.
            $token = new UsernamePasswordToken($utilisateur, null, 'main', $utilisateur->getRoles());
            $this->get('security.token_storage')->setToken($token);
            $this->get('session')->set('_security_main', serialize($token));

            return $this->redirectToRoute('home_page');
        }
        return $this->render('Account/createAccount.html.twig', array('form' => $form->createView()));
    }
}
