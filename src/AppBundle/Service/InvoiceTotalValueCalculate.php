<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManagerInterface;


class InvoiceTotalValueCalculate
{
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function calculateInvoiceTotalValue($invoiceId)
    {
        $invoice = $this->em->getRepository('AppBundle:Invoice')->findOneInvoice($invoiceId)[0];

        if (!$invoice) {

            return false;

        } else {

            $products = [];

            $i=0;
            foreach ($invoice->getProductToInvoice() as $prod) {
                $products[$i]['netPrice'] = $prod->getProduct()->getNetPrice();
                $products[$i]['vatPerCent'] = $prod->getProduct()->getVatPerCent();
                $products[$i]['quantity'] = $prod->getQuantity();
                $i++;
            }

            $netTotalValue = 0;
            foreach ($products as $key => $product) {
                $netTotalValue += $product['netPrice'] * $product['quantity'];
            }

            $grossTotalValue = 0;
            foreach ($products as $key => $product) {
                if ($product['vatPerCent'] != "zw") {
                    $grossTotalValue += ($product['netPrice'] * ($product['vatPerCent'] / 100) + $product['netPrice'])
                        * $product['quantity'];
                } else {
                    $grossTotalValue += $product['netPrice'] * $product['quantity'];
                }
            }
            $invoice->setTotalNet($netTotalValue);
            $invoice->setTotalGross($grossTotalValue);
            $this->em->persist($invoice);
            $this->em->flush();
        }
    }
}