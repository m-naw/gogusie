<?php

namespace Gog\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Gog\Entity\Product;
use Gog\Factory\PaginationFactory;
use Gog\Form\ProductType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @Route("/api")
 */
class ProductController extends AbstractFOSRestController
{
    private PaginationFactory $paginationFactory;

    public function __construct(PaginationFactory $paginationFactory)
    {
        $this->paginationFactory = $paginationFactory;
    }

    /**
     * @Rest\Delete("/products/{id}", name="api_product_delete")
     * @ParamConverter("product", class="Gog:Product")
     */
    public function deleteAction(Product $product)
    {
        if ($product->isRemovable()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($product);
            $manager->flush();

            return $this->handleView($this->view(null, Response::HTTP_NO_CONTENT));
        }

        return $this->handleView($this->view(null, Response::HTTP_UNPROCESSABLE_ENTITY));
    }

    /**
     * @Rest\Get("/products", name="api_product_list")
     */
    public function listAction(Request $request)
    {
        $pagination = $this->paginationFactory->create($request);

        $data = $this->getDoctrine()->getRepository(Product::class)->findPaginated($pagination);
        $view = $this->view($data, Response::HTTP_OK);

        $view->setHeader(
            'X-Next-Page',
            $this->generateUrl('api_product_list', [
                'page' => $pagination->getNextPage(),
                'limit' => $pagination->getLimit(),
            ], UrlGeneratorInterface::ABSOLUTE_URL)
        );

        return $this->handleView($view);
    }

    /**
     * @Rest\Put("/products/{id}", name="api_product_update")
     * @ParamConverter("product", class="Gog:Product")
     */
    public function updateAction(Product $product, Request $request)
    {
        $form = $this->createForm(ProductType::class, $product, [
            'validation_groups' => ['api_update'],
        ]);

        $data = json_decode($request->getContent(), true);

        $form->submit($data, false);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();

            return $this->view($product, Response::HTTP_OK);
        }

        return $this->handleView($this->view($form->getErrors(), Response::HTTP_UNPROCESSABLE_ENTITY));
    }

    /**
     * @Rest\Post("/products", name="api_product_create")
     */
    public function createAction(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product, [
            'validation_groups' => ['api_create'],
        ]);
        $data = json_decode($request->getContent(), true);

        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();

            $manager->persist($product);
            $manager->flush();

            return $this->view($product, Response::HTTP_CREATED);
        }

        return $this->handleView($this->view($form->getErrors(), Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
