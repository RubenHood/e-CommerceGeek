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
use eCommerceBundle\Entity\Cesta;

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
            throw new AccessDeniedException('Solo puede editar su usuario.');
        }
    }

    /**
     * @Route("/cart/logged/show/{id}", name="show_car")
     */
    public function showCartAction($id)
    {
        //recuperamos el entiti manager
        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery(
            'SELECT cesta.id, cesta.cantidad, product.name, product.price, product.img 
            FROM eCommerceBundle:Cesta cesta INNER JOIN eCommerceBundle:Product product where 
            cesta.idProduct = product.id and cesta.idUser = :id'
        )->setParameter('id', $id);

        $products = $query->getResult();

        return $this->render("@eCommerce/User/shopping_cart.html.twig", ["products" => $products]);
    }

    /**
     * @Route("/cart/logged/add/{id}/{cant}", name="add_product_car")
     */
    public function addProductAction(Product $product, $cant, UserInterface $userLogged)
    {
        //recuperamos el entiti manager
        $em = $this->getDoctrine()->getManager();

        $em->getRepository(Cesta::class);

        //creamos la entidad
        $cesta = new Cesta();
        $cesta->setIdUser($userLogged->getId());
        $cesta->setIdProduct($product->getId());
        $cesta->setCantidad($cant);

        //presistimos la entidad
        $em->persist($cesta);
        $em->flush();

        return $this->redirectToRoute('showAll');
    }

    /**
     * @Route("/cart/logged/delete/{id}", name="admin_delete_products")
     * 
     */
    public function deleteProductAction(Cesta $cesta, UserInterface $userLogged)
    {

        //recuperamos el entiti manager
        $em = $this->getDoctrine()->getManager();

        // //obtenemos la referencia al repositorio
        $repository = $em->getRepository(Product::class);

        $repository = $this->getDoctrine()->getManager();
        $repository->remove($cesta);
        $repository->flush();

        return $this->redirectToRoute('show_car', ['id' => $userLogged->getId()]);
    }

    /**
     * @Route("/cart/logged/cant/+/{id}", name="add_quantity_products")
     * 
     */
    public function addQuantityAction(Cesta $id, UserInterface $userLogged)
    {

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Cesta::class);

        $cart = new Cesta();

        $cart = $repository->findOneBy(array('idProduct' => $id, 'idUser' => $userLogged->getId()));

        $cart->setCantidad($cart->getCantidad() + 1);
        $em->persist($cart);
        $em->flush();

        return $this->redirectToRoute('show_car', ['id' => $userLogged->getId()]);
    }

    /**
     * @Route("/cart/logged/cant/-/{id}", name="less_quantity_products")
     * 
     */
    public function lessQuantityAction(Cesta $id, UserInterface $userLogged)
    {

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Cesta::class);

        $cart = new Cesta();

        $cart = $repository->findOneBy(array('idProduct' => $id, 'idUser' => $userLogged->getId()));

        $cart->setCantidad($cart->getCantidad() - 1);
        $em->persist($cart);
        $em->flush();

        if ($cart->getCantidad() < 1) {
            $repository = $this->getDoctrine()->getManager();
            $repository->remove($cart);
            $repository->flush();
        }

        return $this->redirectToRoute('show_car', ['id' => $userLogged->getId()]);
    }

    /**
     * @Route("/cart/logged/deleteall", name="delete_all_cart")
     * 
     */
    public function deleteAllCart(UserInterface $userLogged)
    {
        //recuperamos el entiti manager
        $em = $this->getDoctrine()->getManager();

        //obtenemos la referencia al repositorio
        $repository = $em->getRepository(Cesta::class);

        $carts = $repository->findBy(
            ['idUser' => $userLogged->getId()]
        );

        $em = $this->getDoctrine()->getManager();
        foreach ($carts as $cart) {
            $em->remove($cart);
        }
        $em->flush();

        return $this->redirectToRoute('show_car', ['id' => $userLogged->getId()]);
    }
}
