<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManagerInterface;


class InvoiceNumberGenerator
{

    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function generateInvoiceNumber()
    {
        $lastInvoiceId = $this->em->getRepository('AppBundle:Invoice')->findLastInvoiceId();

        $nextInvoiceNumber = intval($lastInvoiceId['id']) + 1;

        return $nextInvoiceNumber;
    }

}