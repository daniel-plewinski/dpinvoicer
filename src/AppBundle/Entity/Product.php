<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 */
class Product
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
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="Pole nazwa nie może być puste.")
     * @Assert\Regex("/^[a-zA-Z\d-]+$/", message="Nazwa może zawierać tylko litery, cyfry oraz myślnik.")
     */
    private $name;

    /**
     * @ORM\Column(type="decimal", precision=25, scale=2)
     * @Assert\NotBlank(message="Pole cena netto nie może być puste.")
     * @Assert\Range(
     *      min = 0.01,
     *      max = 99999999,
     *      minMessage = "Cena netto nie może być mniejsza niż {{ limit }}.",
     *      maxMessage = "Cena netto nie może być większa niż niż {{ limit }}.",
     * )
     */
    private $netPrice = 0;


    /**
     * @ORM\Column(type="text", length=2)
     * @Assert\NotBlank(message="Wybierz stawkę podatku VAT.")
     */
    private $vatPerCent;

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getNetPrice()
    {
        return $this->netPrice;
    }

    /**
     * @param mixed $netPrice
     */
    public function setNetPrice($netPrice)
    {
        $this->netPrice = $netPrice;
    }

    /**
     * @return mixed
     */
    public function getVatPerCent()
    {
        return $this->vatPerCent;
    }

    /**
     * @param mixed $vatPerCent
     */
    public function setVatPerCent($vatPerCent)
    {
        $this->vatPerCent = $vatPerCent;
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