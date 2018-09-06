<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CompanyRepository")
 */
class Company
{
    /**
     * @Groups("master")
     * @Groups("Company")
     * @Groups("Creditcard")
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups("master")
     * @Groups("Company")
     * @Groups("Creditcard")
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @Groups("Company")
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $slogan;

    /**
     * @Groups("Company")
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $adress;

    /**
     * @Groups("Company")
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $phoneNumber;

    /**
     * @Groups("Company")
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $websiteUrl;

    /**
     * @Groups("Company")
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pictureUrl;

    /**
     * @Groups("Company")
     * @ORM\OneToOne(targetEntity="App\Entity\Master", mappedBy="master", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $master;

    /**
     * @Groups("Company")
     * @ORM\OneToMany(targetEntity="App\Entity\Creditcard", mappedBy="company", cascade={"remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $creditcard;

    public function __construct()
    {
        $this->creditcard = new ArrayCollection();
    }

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

    public function getSlogan(): ?string
    {
        return $this->slogan;
    }

    public function setSlogan(string $slogan): ?string
    {
        $this->slogan = $slogan;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): ?string
    {
        $this->adress = $adress;

        return $this;
    }

    public function getWebsiteUrl(): ?string
    {
        return $this->websiteUrl;
    }

    public function setWebsiteUrl(?string $websiteUrl): ?string
    {
        $this->websiteUrl = $websiteUrl;

        return $this;
    }

    public function getPictureUrl(): ?string
    {
        return $this->pictureUrl;
    }

    public function setPictureUrl(?string $pictureUrl): ?string
    {
        $this->pictureUrl = $pictureUrl;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMaster()
    {
        return $this->master;
    }

    /**
     * @param mixed $master
     */
    public function setMaster($master): void
    {
        $this->master = $master;
    }

    /**
     * @return mixed
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @param mixed $phoneNumber
     */
    public function setPhoneNumber($phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }


    /**
     * @return mixed
     */
    public function getCreditcard()
    {
        return $this->creditcard;
    }

    public function addCreditcard(Creditcard $creditcard): self
    {
        if (!$this->creditcard->contains($creditcard)) {
            $this->creditcard[] = $creditcard;
            $creditcard->setCompany($this);
        }
        return $this;
    }

    public function removeCreditcard(Creditcard $creditcard): self
    {
        if ($this->creditcard->contains($creditcard)) {
            $this->creditcard->removeElement($creditcard);
            // set the owning side to null (unless already changed)
            if ($creditcard->getCompany() === $this) {
                $creditcard->setCompany(null);
            }
        }
        return $this;
    }
}