<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Invoice;
use AppBundle\Entity\ProductToInvoice;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


class InvoicesController extends Controller
{
    /**
     * @Route("/", name="invoices")
     */
    public function index(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $invoices = $em->getRepository('AppBundle:Invoice')->findAllInvoices();

        $paginator = $this->get('knp_paginator');

        $contractors = $em->getRepository('AppBundle:Contractor')->findAllContractors();
        $products = $em->getRepository('AppBundle:Product')->findAllProducts();


        $pagination = $paginator->paginate(
            $invoices, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

        return $this->render("invoices/invoices.html.twig", ['pagination' => $pagination, 'contractors' => $contractors,
            'products' => $products]);
    }


    /**
     * @Route("/invoices/get/{id}", name="getInvoice")
     * @Method("GET")
     */
    public function getInvoice($id)
    {
        $em = $this->getDoctrine()->getManager();

        try {

            $invoice = $em->getRepository('AppBundle:Invoice')->findOneInvoice($id)[0];

            if (!$invoice) {

                $data = ['No results found' => null];

            } else {

                $products = [];

                $i=0;
                foreach ($invoice->getProductToInvoice() as $prod) {
                    $products[$i]['name'] = $prod->getProduct()->getName();
                    $products[$i]['netPrice'] = $prod->getProduct()->getNetPrice();
                    $products[$i]['vatPerCent'] = $prod->getProduct()->getVatPerCent();
                    $products[$i]['quantity'] = $prod->getQuantity();
                    $i++;
                }

                $data = [
                    'id' => $invoice->getId(),
                    'number' => $invoice->getNumber(),
                    'contractorName' => $invoice->getContractor()->getName(),
                    'contractorAddress' => $invoice->getContractor()->getAddress(),
                    'issueDate' => $invoice->getIssueDate()->format('Y-m-d'),
                    'dueByDate' => $invoice->getDueByDate()->format('Y-m-d'),
                    'totalNet' => $invoice->getTotalNet(),
                    'totalGross' => $invoice->getTotalGross(),
                    'products' => $products
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
     * @Route("/invoices/new", name="newInvoice")
     * @Method("POST")
     */
    public function newInvoice(Request $request)
    {
//        $name = $request->get('name');

        $em = $this->getDoctrine()->getManager();

        $contractor = $em->getRepository('AppBundle:Contractor')->find(16);

        $invoice = new Invoice();
        $invoice->setContractor($contractor);
        $invoice->setNumber(3);
        $invoice->setIssueDate(new \DateTime('now'));
        $invoice->setDueByDate(new \DateTime('2019-04-20'));

        $em->persist($invoice);
        $em->flush();

        $invoiceId = $invoice->getId();
        $lastInvoice = $em->getRepository('AppBundle:Invoice')->find($invoiceId);

        $productToInvoice = new ProductToInvoice();

        $productToInvoice->setInvoice($lastInvoice);

        $product = $em->getRepository('AppBundle:Product')->find(2);
        $productToInvoice->setProduct($product);
        $productToInvoice->setQuantity(9);

        $em->persist($productToInvoice);
        $em->flush();

        return new Response('OK', 201);
    }

    /**
     * @param $id
     * @Route("/invoice/delete/{id}", name="deleteInvoice")
     * @Method("DELETE")
     * @return JsonResponse
     */
    public function deleteInvoice($id)
    {
        $em = $this->getDoctrine()->getManager();

        try {

            $invoice = $em->getRepository('AppBundle:Invoice')->find($id);

            if (!$invoice) {

                return new JsonResponse([ 'success' => false,]);

            } else {

                $invoice->setStatus('D');

                $em->persist($invoice);
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
