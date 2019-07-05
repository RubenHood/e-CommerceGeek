<?php

namespace eCommerceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use eCommerceBundle\Entity\Product;
use eCommerceBundle\Entity\User;
use eCommerceBundle\Form\UserType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\BrowserKit\Request;

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
     * @Route("/login", name="login", methods={"GET", "POST"})
     */
    public function loginAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        // 1) build the form
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            // 4) save the User!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('showAll');
        }

        return $this->render('@eCommerce/User/new.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }
}
