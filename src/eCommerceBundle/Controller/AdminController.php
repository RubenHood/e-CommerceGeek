<?php

namespace eCommerceBundle\Controller;

use eCommerceBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use eCommerceBundle\Form\UserType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Admin controller.
 *
 * @Route("/admin")
 */
class AdminController extends Controller
{
    /**
     * @Route("/all", name="user_index")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository(User::class)->findAll();

        return $this->render('@eCommerce/User/index.html.twig', ['users' => $users]);
    }

    /**
     * @Route("/show/{id}", name="user_show")
     */
    public function showAction(User $userRequest, UserInterface $userLogged = null)
    {
        // comprobamos si el usuario que realiza la peticion es el mismo que está logueado o es el admin
        if($userLogged == $userRequest || $this->isGranted('ROLE_ADMIN')){
            return $this->render('@eCommerce/User/show.html.twig', ['user' => $userRequest]);
        }else{
            throw $this->createAccessDeniedException('Solo puede editar su usuario.');
        }
    }

    /**
     * @Route("/edit/{id}", name="user_edit")
     */
    public function editAction(Request $request, UserPasswordEncoderInterface $passwordEncoder, User $user)
    {
        $editForm = $this->createForm(UserType::class, $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('@eCommerce/User/edit.html.twig', array(
            'user' => $user,
            'edit_form' => $editForm->createView()
        ));
    }

    /**
     * @Route("/delete/{id}", name="user_delete")
     * 
     */
    public function deleteAction( User $user)
    {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();

        return $this->redirectToRoute('user_index');
    }
}
