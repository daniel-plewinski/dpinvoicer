<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Invoice;
use AppBundle\Entity\ProductToInvoice;
use AppBundle\Service\InvoiceNumberGenerator;
use AppBundle\Service\InvoiceTotalValueCalculate;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


/**
 * Class InvoicesController
 * @package AppBundle\Controller
 */
class InvoicesController extends Controller
{

    public function __construct()
    {

    }

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
    public function newInvoice(
        Request $request,
        InvoiceNumberGenerator $invoiceNumberGenerator,
        InvoiceTotalValueCalculate $invoiceTotalValueCalculate)
    {
        $contractorId = $request->get('contractor');
        $invoiceDueByDate = $request->get('invoiceDueByDate');

        $em = $this->getDoctrine()->getManager();

        $contractor = $em->getRepository('AppBundle:Contractor')->find($contractorId);

        $invoice = new Invoice();
        $invoice->setContractor($contractor);
        $invoice->setNumber($invoiceNumberGenerator->generateInvoiceNumber());
        $invoice->setIssueDate(new \DateTime('now'));
        $invoice->setDueByDate(new \DateTime($invoiceDueByDate));

        $addProducts = [];
        $index = 0;
        if ($request->get('invoiceProduct1') && $request->get('invoiceProductQuantity1')) {
            $addProducts[$index]['productId'] = $request->get('invoiceProduct1');
            $addProducts[$index]['productQuantity'] = $request->get('invoiceProductQuantity1');
            $index++;
        }

        if ($request->get('invoiceProduct2') && $request->get('invoiceProductQuantity2')) {
            $addProducts[$index]['productId'] = $request->get('invoiceProduct2');
            $addProducts[$index]['productQuantity'] = $request->get('invoiceProductQuantity2');
        }


        if (empty($addProducts)) {

            return false;
        }

        try {

            $em->persist($invoice);
            $em->flush();

            $invoiceId = $invoice->getId();
            $lastInvoice = $em->getRepository('AppBundle:Invoice')->find($invoiceId);

            foreach ($addProducts as $addProduct) {
                $productToInvoice = new ProductToInvoice();
                $productToInvoice->setInvoice($lastInvoice);
                $product = $em->getRepository('AppBundle:Product')->find($addProduct['productId']);
                $productToInvoice->setProduct($product);
                $productToInvoice->setQuantity($addProduct['productQuantity']);

                $em->persist($productToInvoice);
            }

            $em->flush();
            $em->clear();

            $invoiceTotalValueCalculate->calculateInvoiceTotalValue($invoiceId);

            return new JsonResponse([
                'success' => true,
            ]);

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

    /**
     * @param $id
     * @Route("/invoices/delete/{id}", name="deleteInvoice")
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

                $productToInvoiceArr = $em->getRepository('AppBundle:ProductToInvoice')->findByInvoice($invoice);

                foreach ($productToInvoiceArr as $productToInvoice) {
                    $productToInvoice->setStatus('D');
                }

                $em->persist($invoice);
                $em->persist($productToInvoice);
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
