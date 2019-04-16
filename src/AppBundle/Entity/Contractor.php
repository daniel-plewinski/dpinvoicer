<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as ContractorAssert;

/**
 * Contractor
 *
 * @ORM\Table(name="contractor")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ContractorRepository")
 */
class Contractor
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
     * @var integer
     * @ORM\Column(type="bigint")
     * @Assert\NotBlank(message="Pole nip nie może być puste.")
     * @ContractorAssert\IsNip()
     */
    private $nip;

    /**
     * @var string
     * @ORM\Column(type="string", length=500)
     * @Assert\NotBlank(message="Pole adres nie może być puste.")
     * @Assert\Regex("/^[a-zA-Z\.\/\d-]+$/", message="Adres zawiera niedozwolony znak.")
     */
    private $address;

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
     * @return int
     */
    public function getNip()
    {
        return $this->nip;
    }

    /**
     * @param int $nip
     */
    public function setNip($nip)
    {
        $this->nip = $nip;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
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

