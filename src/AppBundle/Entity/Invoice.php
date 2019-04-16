<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Invoice
 *
 * @ORM\Table(name="invoice")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\InvoiceRepository")
 */
class Invoice
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     *@ORM\Column(type="string", length=100, unique=true)
     */
    private $number;

    /**
     * @var Contractor
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Contractor")
     * @ORM\JoinColumn(name="contractor", referencedColumnName="id")
     * @Assert\NotBlank(message="Wybierz kontrahenta.")
     */
    private $contractor;

    /**
     * @ORM\Column(type="datetime")
     */
    private $issueDate;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="Pole data jest obowiązkowe.")
     */
    private $dueByDate;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=2)
     */
    private $totalNet = 0;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=2)
     */
    private $totalGross = 0;

    /**
     * @var string
     * @ORM\Column(name="status__", type="string", length=1, nullable=true)
     */
    private $status;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;


    /**
     * @var ProductToInvoice
     *
     * @ORM\OneToMany(targetEntity="ProductToInvoice", mappedBy="invoice")
     */
    private $productToInvoice;


    public function __construct()
    {
        $this->productToInvoice = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param string $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * @return Contractor
     */
    public function getContractor()
    {
        return $this->contractor;
    }

    /**
     * @param Contractor $contractor
     */
    public function setContractor($contractor)
    {
        $this->contractor = $contractor;
    }

    /**
     * @return mixed
     */
    public function getIssueDate()
    {
        return $this->issueDate;
    }

    /**
     * @param mixed $issueDate
     */
    public function setIssueDate($issueDate)
    {
        $this->issueDate = $issueDate;
    }

    /**
     * @return mixed
     */
    public function getDueByDate()
    {
        return $this->dueByDate;
    }

    /**
     * @param mixed $dueByDate
     */
    public function setDueByDate($dueByDate)
    {
        $this->dueByDate = $dueByDate;
    }

    /**
     * @return mixed
     */
    public function getTotalNet()
    {
        return $this->totalNet;
    }

    /**
     * @param mixed $totalNet
     */
    public function setTotalNet($totalNet)
    {
        $this->totalNet = $totalNet;
    }

    /**
     * @return mixed
     */
    public function getTotalGross()
    {
        return $this->totalGross;
    }

    /**
     * @param mixed $totalGross
     */
    public function setTotalGross($totalGross)
    {
        $this->totalGross = $totalGross;
    }

    /**
     * @return \AppBundle\Entity\ProductToInvoice
     */
    public function getProductToInvoice()
    {
        return $this->productToInvoice;
    }

    /**
     * @param \AppBundle\Entity\ProductToInvoice $productToInvoice
     */
    public function setProductToInvoice($productToInvoice)
    {
        $this->productToInvoice = $productToInvoice;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}

