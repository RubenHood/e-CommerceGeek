<?php

namespace eCommerceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use eCommerceBundle\Entity\Product;
use eCommerceBundle\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use eCommerceBundle\Form\UserType;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Finder\Exception\AccessDeniedException;

/**
 * @Route("/")
 */
class UserController extends Controller
{
    /**
     * @Route("/", methods={"GET"}, name="showAll")
     */
    public function getAllAction()
    {
        //recuperamos el entiti manager
        $em = $this->getDoctrine()->getManager();

        //obtenemos la referencia al repositorio
        $repository = $em->getRepository(Product::class);

        $products = $repository->findAll();

        return $this->render("@eCommerce/Product/all_products.html.twig", ["products" => $products]);
    }

    /**
     * @Route("/{id}", methods={"GET"}, name="showOne")
     */
    public function getOneAction(Product $product)
    {
        //recuperamos el entiti manager
        $em = $this->getDoctrine()->getManager();

        // //obtenemos la referencia al repositorio
        $repository = $em->getRepository(Product::class);

        $products = $repository->find($product);

        return $this->render("@eCommerce/Product/one_products.html.twig", ["product" => $product]);
    }

    /**
     *
     * @Route("/user/new", name="newUser")
     * 
     */
    public function registroAction(Request $request, UserPasswordEncoderInterface $passwordEncoder, AuthenticationUtils $authenticationUtils)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            // get the login error if there is one
            $error = $authenticationUtils->getLastAuthenticationError();

            return $this->render('@eCommerce/User/login.html.twig', [
                'last_username' => $user->getEmail(),
                'error'         => $error,
            ]);
        }

        return $this->render('@eCommerce/User/registro.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     *
     * @Route("/user/login", name="login")
     * 
     */
    public function loginAction(AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('@eCommerce/User/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    /**
     * @Route("/user/logged/edit/{id}", name="edit_user")
     */
    public function editAction(Request $request, UserInterface $userLogged = null, UserPasswordEncoderInterface $passwordEncoder, User $userRequest)
    {
        if ($userLogged == $userRequest) {
            $editForm = $this->createForm(UserType::class, $userRequest);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $password = $passwordEncoder->encodePassword($userRequest, $userRequest->getPlainPassword());
                $userRequest->setPassword($password);

                $em = $this->getDoctrine()->getManager();
                $em->persist($userRequest);
                $em->flush();

                return $this->redirectToRoute('user_index');
            }

            return $this->render('@eCommerce/User/edit.html.twig', array(
                'user' => $userRequest,
                'edit_form' => $editForm->createView()
            ));
        } else {
            throw new AccessDeniedException('Solo puede editar su usuario');
        }
    }
}
