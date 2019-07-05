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

class ProductsController extends Controller
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
    public function registroAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
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

            //creamos la variable sesion
            $session = new Session();

            // set and get session attributes
            $session->set('user', $user->getUsername());
            $session->set('email', $user->getEmail());
            // $session->set('role', $user->getRoles());

            return $this->redirectToRoute('showAll');
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
}
