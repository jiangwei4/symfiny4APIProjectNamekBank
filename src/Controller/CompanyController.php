<?php

namespace App\Controller;

use App\Entity\Company;
use App\Repository\CompanyRepository;
use App\Repository\CreditcardRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


class CompanyController extends FOSRestController
{
    private $companyRepository;
    private $em;
    public function __construct(CompanyRepository $companyRepository,CreditcardRepository $creditcardRepository, EntityManagerInterface $em)
    {
        $this->companyRepository = $companyRepository;
        $this->creditcardRepository = $creditcardRepository;
        $this->em = $em;
    }

    private function MasterAdminDroit()
    {
        if (in_array("ROLE_ADMIN",$this->getUser()->getRoles()) ) {
            $return = true;
        } else {
            $return = false;
        }
        return $return;
    }


    private function PostError($validationErrors){
        $error = array("error :");
        /** @var ConstraintViolationListInterface $validationErrors */
        /** @var ConstraintViolation $constraintViolation */
        foreach ($validationErrors as $constraintViolation) {
            $message = $constraintViolation->getMessage();
            $propertyPath = $constraintViolation->getPropertyPath();
            array_push($error,$propertyPath.' => '.$message);
        }
        return $error;
    }


    /**
     * @SWG\Parameter(
     *     name="AUTH-TOKEN",
     *     in="header",
     *     type="string",
     *     description="Api Token"
     * )
     * @SWG\Response(response=200, description="")
     * @SWG\Tag(name="Company")
     * @Rest\View(serializerGroups={"Company"})
     */
    public function getCompanysAction()
    {
        if($this->getUser() !== null )
        {
            if ($this->MasterAdminDroit()) {
                return $this->view($this->companyRepository->findAll());
            }
            return $this->view('Not Logged for this user or not an Admin', 403);
        } else {
            return $this->view('Not Logged', 401);
        }
    }
    /**
     * @SWG\Response(response=200, description="")
     * @SWG\Tag(name="Company")
     * @Rest\View(serializerGroups={"Company"})
     *
     */
    public function getCompanyAction(Company $company)
    {
      return $this->view($company);
    }


    /**
     * @SWG\Response(response=200, description="")
     * @SWG\Tag(name="Company")
     * @Rest\View(serializerGroups={"Company"})
     * @Rest\Post("/Company")
     * @ParamConverter("company", converter="fos_rest.request_body")
     */
    public function postCompanysAction(Company $company, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $validationErrors = $validator->validate($company);
        if(!($validationErrors->count() > 0) ){
            $this->em->persist($company);
            $this->em->flush();
            return $this->view($company,200);
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
     * @SWG\Tag(name="Company")
     * @Rest\View(serializerGroups={"Company"})
     */
    public function putCompanyAction(Request $request, $id, ValidatorInterface $validator)
    {
        $company = $this->companyRepository->find($id);
        if($company === null){
            return $this->view('Compagny does note existe', 404);
        }
        if ($this->getUser() == $company->getMaster() || $this->MasterAdminDroit()) {
            /** @var Company $us */
            $us = $this->companyRepository->find($id);
            $name = $request->get('name');
            $slogan = $request->get('slogan');
            $adress = $request->get('adress');
            $websiteUrl = $request->get('websiteUrl');
           $pictureUrl = $request->get('pictureUrl');
           $phoneNumber = $request->get('phoneNumber');
            if (isset($name)) {
                $us->setName($name);
            }
            if (isset($slogan)) {
                $us->setSlogan($slogan);
            }
            if (isset($adress)) {
                $us->setAdress($adress);
            }
            if (isset($websiteUrl)) {
                $us->setWebsiteUrl($websiteUrl);
            }
            if (isset($pictureUrl)) {
                $us->setPictureUrl($pictureUrl);
            }
            if (isset($phoneNumber)) {
                $us->setPhoneNumber($phoneNumber);
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
     * @SWG\Tag(name="Company")
     * @Rest\View(serializerGroups={"Company"})
     */
    public function deleteCompanyAction($id)
    {
        /** @var Company $us */
        $company = $this->companyRepository->findBy(["id"=>$id]);
        if($company === []){
            return $this->view('company does note existe', 404);
        }
        if($this->getUser() !== null ) {
            $us = $this->companyRepository->find($id);
            if ($us->getMaster() === $this->getUser() || $this->MasterAdminDroit()) {
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
