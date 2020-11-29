<?php

namespace Gog\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Gog\Entity\Cart;
use Gog\Entity\CartProduct;
use Gog\Entity\Product;
use Gog\Factory\CartFactory;
use Gog\Factory\CartProductFactory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api")
 */
class CartController extends AbstractFOSRestController
{
    private CartFactory $cartFactory;

    private CartProductFactory $cartProductFactory;

    private ValidatorInterface $validator;

    public function __construct(
        CartFactory $cartFactory,
        CartProductFactory $cartProductFactory,
        ValidatorInterface $validator
    ) {
        $this->cartFactory = $cartFactory;
        $this->cartProductFactory = $cartProductFactory;
        $this->validator = $validator;
    }

    /**
     * @Rest\Get("/carts/{id}", name="api_cart_get")
     * @ParamConverter("cart", class="Gog:Cart")
     */
    public function getAction(Cart $cart)
    {
        $view = $this->view($cart, Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Rest\Delete("/carts/{cartId}/product/{productId}",
     *     name="api_cart_remove_product",
     *     requirements={"cartId"="\d+", "productId"="\d+"}
     * )
     * @ParamConverter("cartProduct",
     *     class="Gog:CartProduct",
     *     options={"mapping" : {"cartId" : "cartId", "productId" : "productId"},
     *     "map_method_signature" = true,
     *     "repository_method" : "findOneByCartAndProduct"}
     * )
     */
    public function removeProductAction(CartProduct $cartProduct)
    {
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($cartProduct);
        $manager->flush();

        return $this->handleView($this->view(null, Response::HTTP_NO_CONTENT));
    }

    /**
     * @Rest\Post("/carts/{cartId}/product/{productId}",
     *     name="api_cart_add_product",
     *     requirements={"cartId"="\d+", "productId"="\d+"}
     * )
     * @ParamConverter("cart",
     *     class="Gog:Cart",
     *     options={"id" = "cartId"}
     * )
     * @ParamConverter("product",
     *     class="Gog:Product",
     *     options={"id" = "productId"}
     * )
     */
    public function addProductAction(Cart $cart, Product $product)
    {
        $cartProduct = $this->cartProductFactory->create($cart, $product);

        $errors = $this->validator->validate($cart);

        if (count($errors) > 0) {
            return $this->handleView($this->view($errors, Response::HTTP_UNPROCESSABLE_ENTITY));
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($cartProduct);
        $manager->flush();

        $view = $this->view($cart, Response::HTTP_CREATED);

        return $this->handleView($view);
    }

    /**
     * @Rest\Post("/carts", name="api_cart_create")
     */
    public function createAction()
    {
        $cart = $this->cartFactory->create();
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($cart);
        $manager->flush();

        return $this->view($cart, Response::HTTP_CREATED);
    }
}
