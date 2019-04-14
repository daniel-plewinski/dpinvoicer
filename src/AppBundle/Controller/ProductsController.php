<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class ProductsController extends Controller
{
    /**
     * @Route("/products/", name="products")
     */
    public function index(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository('AppBundle:Product')->findAllProducts();

        $paginator = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $products, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

        return $this->render("products/products.html.twig", ['pagination' => $pagination]);
    }


    /**
     * @Route("/products/get/{id}", name="getProduct")
     * @Method("GET")
     */
    public function getProduct($id)
    {
        $em = $this->getDoctrine()->getManager();

        try {

            $product = $em->getRepository('AppBundle:Product')->findOneProduct($id)[0];

            if (!$product) {

                $data = ['No results found' => null];

            } else {

                $data = [
                    'id' => $product->getId(),
                    'name' => $product->getName(),
                    'netPrice' => $product->getNetPrice(),
                    'vatPerCent' => $product->getVatPerCent()
                ];
            }

            return new JsonResponse([
                'success' => true,
                'data'    => $data
            ]);

        } catch (Exception $exception) {

            return new JsonResponse([
                'success' => false,
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * @Route("/products/new", name="newProduct")
     * @Method("POST")
     */
    public function newProduct(Request $request)
    {
        $name = $request->get('name');
        $netPrice = $request->get('netPrice');
        $vatPerCent = $request->get('vatPerCent');

        $product = new Product();
        $product->setName($name);
        $product->setNetPrice($netPrice);
        $product->setVatPerCent($vatPerCent);

        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();

        return new Response('OK', 201);
    }


    /**
     * @Route("/products/update/{id}", name="updateProduct")
     * @Method("PATCH")
     */
    public function updateProduct(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        try {

            $product = $em->getRepository('AppBundle:Product')->find($id);

            if (!$product) {

                return new JsonResponse([ 'success' => false,]);

            } else {

                $name = $request->get('name');
                $netPrice = $request->get('netPrice');
                $vatPerCent = $request->get('vatPerCent');

                if ($name) {
                    $product->setName($name);
                }

                if ($netPrice) {
                    $product->setNetPrice($netPrice);
                }

                if ($vatPerCent) {
                    $product->setVatPerCent($vatPerCent);
                }

                $em->persist($product);
                $em->flush();
            }

            return new JsonResponse([
                'success' => true,
            ]);

        } catch (Exception $exception) {

            return new JsonResponse([
                'success' => false,
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * @param $id
     * @Route("/products/delete/{id}", name="deleteProduct")
     * @Method("DELETE")
     * @return JsonResponse
     */
    public function deleteProduct($id)
    {
        $em = $this->getDoctrine()->getManager();

        try {

            $product = $em->getRepository('AppBundle:Product')->find($id);

            if (!$product) {

                return new JsonResponse([ 'success' => false,]);

            } else {

                $product->setStatus('D');

                $em->persist($product);
                $em->flush();

                return new JsonResponse([
                    'success' => true,
                ]);
            }

        } catch (Exception $exception) {

            return new JsonResponse(
                [
                    'success' => false,
                    'code' => $exception->getCode(),
                    'message' => $exception->getMessage(),
                ]
            );
        }
    }
}
