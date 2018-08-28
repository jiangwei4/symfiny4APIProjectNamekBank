<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CreditcardRepository")
 */
class Creditcard
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * Assert\NotBlank()
     */
    private $creditcardType;

    /**
     * @ORM\Column(type="string", length=255)
     * Assert\NotBlank()
     */
    private $creditcardNumber;

    /**
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

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCreditcardType(): ?string
    {
        return $this->creditcardType;
    }

    public function setCreditcardType(string $creditcardType): self
    {
        $this->creditcardType = $creditcardType;

        return $this;
    }

    public function getCreditcardNumber(): ?string
    {
        return $this->creditcardNumber;
    }

    public function setCreditcardNumber(string $creditcardNumber): self
    {
        $this->creditcardNumber = $creditcardNumber;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(string $company): self
    {
        $this->company = $company;

        return $this;
    }
}
