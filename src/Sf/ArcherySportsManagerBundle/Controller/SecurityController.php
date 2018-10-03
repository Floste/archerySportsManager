<?php

namespace Sf\ArcherySportsManagerBundle\Controller;

use Sf\ArcherySportsManagerBundle\Entity\User;
use Sf\ArcherySportsManagerBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Structureuser controller.
 *
 */
class SecurityController extends Controller
{

    /**
     * Authentification des utilisateurs partenaires
     */
    public function loginAction(Request $request)
    {
        $authUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('SfArcherySportsManagerBundle:Default:login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(User::class)->findAll();

        return $this->render('SfArcherySportsManagerBundle:Default:index.html.twig', array(
            'users' => $users,
        ));
    }

    public function editAction(Request $request, User $user)
    {
        $editForm = $this->createForm(UserType::class, $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            if(""!=$user->getPlainPassword()){
                $encodePassword = $this->get('security.password_encoder')->encodePassword($user,$user->getPlainPassword());
                $user->setPassword($encodePassword);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('azimut_abiboisArchives_security_index');
        }

        return $this->render('AzimutAbiboisArchivesSecurityBundle:Default:edit.html.twig', array(
            'user' => $user,
            'form' => $editForm->createView(),
        ));
    }

    public function newAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $encodePassword = $this->get('security.password_encoder')->encodePassword($user,$user->getPlainPassword());
            $user->setPassword($encodePassword);
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('azimut_abiboisArchives_security_index');
        }

        return $this->render('AzimutAbiboisArchivesSecurityBundle:Default:edit.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }

    public function deleteAction(Request $request, User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        $this->addFlash("success","Utilisateur supprimé");

        return $this->redirectToRoute('azimut_abiboisArchives_security_index');

    }

}
