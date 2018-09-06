<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CreditcardRepository")
 */
class Creditcard
{
    /**
     * @Groups("Creditcard")
     * @Groups("Company")
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups("Company")
     *  @Groups("Creditcard")
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @Groups("Creditcard")
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $creditcardType;

    /**
     * @Groups("Creditcard")
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $creditcardNumber;

    /**
     * @Groups("Creditcard")
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="creditcard")
     */
    private $company;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): ?string
    {
        $this->name = $name;

        return $this;
    }

    public function getCreditcardType(): ?string
    {
        return $this->creditcardType;
    }

    public function setCreditcardType(string $creditcardType): ?string
    {
        $this->creditcardType = $creditcardType;

        return $this;
    }

    public function getCreditcardNumber(): ?string
    {
        return $this->creditcardNumber;
    }

    public function setCreditcardNumber(string $creditcardNumber): ?string
    {
        $this->creditcardNumber = $creditcardNumber;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param mixed $company
     */
    public function setCompany($company): void
    {
        $this->company = $company;
    }


}
