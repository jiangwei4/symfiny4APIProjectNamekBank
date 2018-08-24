<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Faker\Test\Provider\Collection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CompanyRepository")
 */
class Company
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups("Company")
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Groups("Company")
     * @ORM\Column(type="string", length=255)
     */
    private $slogan;

    /**
     * @Groups("Company")
     * @ORM\Column(type="string", length=255)
     */
    private $adress;

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
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Creditcard", mappedBy="company", cascade={"remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $creditcard;

    public function __construct()
    {
       $this->creditcard =  new ArrayCollection();
    }

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

    public function getSlogan(): ?string
    {
        return $this->slogan;
    }

    public function setSlogan(string $slogan): self
    {
        $this->slogan = $slogan;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getWebsiteUrl(): ?string
    {
        return $this->websiteUrl;
    }

    public function setWebsiteUrl(?string $websiteUrl): self
    {
        $this->websiteUrl = $websiteUrl;

        return $this;
    }

    public function getPictureUrl(): ?string
    {
        return $this->pictureUrl;
    }

    public function setPictureUrl(?string $pictureUrl): self
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
    /**
     * @SWG\Response(response=200, description="")
     * @SWG\Tag(name="master")
     * @Rest\View(serializerGroups={"master"})
     * @Rest\Post("/masters")
     * @ParamConverter("user", converter="fos_rest.request_body")
     */
    public function postMastersAction(Master $master, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $validationErrors = $validator->validate($master);
        if(!($validationErrors->count() > 0) ){
            $this->em->persist($master);
            $this->em->flush();
            return $this->view($master,200);
        } else {
            return $this->view($this->PostError($validationErrors),400);
        }
    }

    /**
     *  @SWG\Parameter(
     *     name="AUTH-TOKEN",
     *     in="header",
     *     type="string",
     *     description="Api Token"
     * )
     * @SWG\Response(response=200, description="")
     * @SWG\Tag(name="master")
     * @Rest\View(serializerGroups={"master"})
     */
    public function putMasterAction(Request $request, $id, ValidatorInterface $validator)
    {
        $users = $this->masterRepository->find($id);
        if($users === null){
            return $this->view('User does note existe', 404);
        }
        // dump($this->getUser());die;
        if ($id == $this->getUser()->getId() || $this->MasterDroit()) {
            /** @var Master $us */
            $us = $this->masterRepository->find($id);
            $firstname = $request->get('firstname');
            $lastname = $request->get('lastname');
            $email = $request->get('email');
            $company = $request->get('birthday');
            if (isset($firstname)) {
                $us->setFirstname($firstname);
            }
            if (isset($lastname)) {
                $us->setLastname($lastname);
            }
            if (isset($email)) {
                $us->setEmail($email);
            }
            if (isset($company)) {
                $us->setCompany($company);
            }
            $this->em->persist($us);
            $validationErrors = $validator->validate($us);
            if(!($validationErrors->count() > 0) ) {
                $this->em->flush();
                return $this->view("ok",200);
            } else {
                return $this->view($this->PostError($validationErrors),401);
            }
        } else {
            return $this->view('Not the same user or tu n as pas les droits',401);
        }
    }
    /**
     * @SWG\Parameter(
     *     name="AUTH-TOKEN",
     *     in="header",
     *     type="string",
     *     description="Api Token"
     * )
     * @SWG\Response(response=200, description="")
     * @SWG\Tag(name="master")
     * @Rest\View(serializerGroups={"master"})
     */
    public function deleteMasterAction($id)
    {
        /** @var Master $us */
        $users = $this->masterRepository->findBy(["id"=>$id]);
        if($users === []){
            return $this->view('User does note existe', 404);
        }
        if($this->getUser() !== null ) {
            $us = $this->masterRepository->find($id);
            if ($us === $this->getUser() || $this->MasterDroit()) {
                $this->em->remove($us);
                $this->em->flush();
            } else {
                return $this->view('Not the same user or tu n as pas les droits',401);
            }
        } else {
            return $this->view('Not Logged', 401);
        }
    }
}
