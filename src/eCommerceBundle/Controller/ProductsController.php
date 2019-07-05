<?php

namespace eCommerceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use eCommerceBundle\Entity\Product;

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
}
