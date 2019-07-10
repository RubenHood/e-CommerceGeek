<?php

namespace eCommerceBundle\Controller;

use eCommerceBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use eCommerceBundle\Form\UserType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use eCommerceBundle\Entity\Product;
use eCommerceBundle\Form\ProductType;

/**
 * Admin controller.
 *
 * @Route("/admin")
 */
class AdminController extends Controller
{
    /**
     * @Route("/users", name="user_index")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository(User::class)->findAll();

        return $this->render('@eCommerce/Admin/admin_all_users.html.twig', ['users' => $users]);
    }

    /**
     * @Route("/users/show/{id}", name="admin_show_user")
     */
    public function showAction(User $userRequest)
    {
        // comprobamos si el usuario que realiza la peticion es el mismo que está logueado o es el admin
        return $this->render('@eCommerce/Admin/admin_show_user.html.twig', ['user' => $userRequest]);
    }

    /**
     * @Route("/users/edit/{id}", name="admin_edit_user")
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

        return $this->render('@eCommerce/Admin/admin_edit_user.html.twig', array(
            'user' => $user,
            'edit_form' => $editForm->createView()
        ));
    }

    /**
     * @Route("/users/delete/{id}", name="admin_delete_user")
     * 
     */
    public function deleteAction(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('user_index');
    }

    /**
     * @Route("/products", methods={"GET"}, name="adminAllProduct")
     */
    public function adminAllProductsAction()
    {
        //recuperamos el entiti manager
        $em = $this->getDoctrine()->getManager();

        //obtenemos la referencia al repositorio
        $repository = $em->getRepository(Product::class);

        $products = $repository->findAll();

        return $this->render("@eCommerce/Admin/admin_all_products.html.twig", ["products" => $products]);
    }

    /**
     * @Route("/products/show/{id}", name="admin_show_products")
     */
    public function showProductAction(Product $product)
    {
        // comprobamos si el usuario que realiza la peticion es el mismo que está logueado o es el admin
        return $this->render('@eCommerce/Admin/admin_show_product.html.twig', ['product' => $product]);
    }

    /**
     * @Route("/products/edit/{id}", name="admin_edit_products")
     */
    public function editProductAction(Request $request, Product $product)
    {
        $editForm = $this->createForm(ProductType::class, $product);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('adminAllProduct');
        }

        return $this->render('@eCommerce/Admin/admin_edit_product.html.twig', array(
            'user' => $product,
            'edit_form' => $editForm->createView()
        ));
    }

    /**
     * @Route("/products/delete/{id}", name="admin_delete_products")
     * 
     */
    public function deleteProductAction(Product $product)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();

        return $this->redirectToRoute('adminAllProduct');
    }
}
