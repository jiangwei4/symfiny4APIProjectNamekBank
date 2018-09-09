<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Creditcard;
use App\Entity\Master;
use App\Repository\CreditcardRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use FOS\RestBundle\Controller\Annotations as Rest;

class CreditcardController extends FOSRestController
{
    private $creditcardRepository;
    private $em;
    public function __construct(CreditcardRepository $creditcardRepository, EntityManagerInterface $em)
    {
        $this->creditcardRepository = $creditcardRepository;
        $this->em = $em;
    }

    private function MasterAdminDroitMaster(Master $master)
    {
        if ($this->getUser() === $master || in_array("ROLE_ADMIN",$this->getUser()->getRoles()) ) {
            $return = true;
        } else {
            $return = false;
        }
        return $return;
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
     * @SWG\Tag(name="Creditcard")
     * @Rest\View(serializerGroups={"Creditcard"})
     */
    public function getCreditcardsAction()
    {
        if($this->getUser() !== null )
        {
            $company = $this->getUser()->getCompany();
            return $this->view($this->creditcardRepository->findBy(["company"=>$company]));
        } else {
            return $this->view('Not Logged', 401);
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
     * @SWG\Tag(name="Creditcard")
     * @Rest\View(serializerGroups={"Creditcard"})
     */
    public function getCreditcardsofallcompanyAction()
    {
        if($this->getUser() !== null )
        {
            if ($this->MasterAdminDroit()) {
                return $this->view($this->creditcardRepository->findAll());
            }
            return $this->view('Not Logged for this user or not an Admin', 403);
        } else {
            return $this->view('Not Logged', 401);
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
     * @SWG\Tag(name="Creditcard")
     * @Rest\View(serializerGroups={"Creditcard"})
     *
     */
    public function getCreditcardAction(Creditcard $creditcard)
    {
        return $this->view($creditcard);
    }



    /**
     * @SWG\Parameter(
     *     name="AUTH-TOKEN",
     *     in="header",
     *     type="string",
     *     description="Api Token"
     * )
     * @SWG\Response(response=200, description="")
     * @SWG\Tag(name="Creditcard")
     * @Rest\View(serializerGroups={"Creditcard"})
     * @Rest\Post("/Creditcard")
     * @ParamConverter("creditcard", converter="fos_rest.request_body")
     */
    public function postCreditcardsAction(Creditcard $creditcard, ValidatorInterface $validator)
    {
        if ($this->getUser() != null) {
            if ($this->getUser() == $creditcard->getCompany()->getMaster() || $this->MasterAdminDroit()) {
                $validationErrors = $validator->validate($creditcard);
                if (!($validationErrors->count() > 0)) {
                    $this->em->persist($creditcard);
                    $this->em->flush();
                    return $this->view($creditcard, 200);
                } else {
                    return $this->view($this->PostError($validationErrors), 400);
                }
            } else {
                return $this->view('Not the same user or tu n as pas les droits', 401);
            }
        } else {
            return $this->view('Not Logged', 401);
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
     * @SWG\Tag(name="Creditcard")
     * @Rest\View(serializerGroups={"Creditcard"})
     */
    public function putCreditcardAction(Request $request, $id, ValidatorInterface $validator)
    {
        $creditcard = $this->creditcardRepository->find($id);
        if($creditcard === null){
            return $this->view('Creditcard does note existe', 404);
        }
        if ($creditcard->getCompany()->getMaster() == $this->getUser() || $this->MasterAdminDroit()) {
            /** @var Creditcard $creditcard */
            $creditcard = $this->creditcardRepository->find($id);
             $name = $request->get('$name');
             $creditcardType = $request->get('$creditcardType');
             $creditcardNumber = $request->get('$creditcardNumber');
             if (isset($name)) {
                 $creditcard->setName($name);
             }
             if (isset($creditcardType)) {
                 $creditcard->setCreditcardType($creditcardType);
             }
             if (isset($creditcardNumber)) {
                 $creditcard->setCreditcardNumber($creditcardNumber);
             }
            $this->em->persist($creditcard);
            $validationErrors = $validator->validate($creditcard);
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
     * @SWG\Tag(name="Creditcard")
     * @Rest\View(serializerGroups={"Creditcard"})
     */
    public function deleteCreditcardAction($id)
    {

        /** @var Creditcard $creditcard */
        $creditcard = $this->creditcardRepository->findBy(["id"=>$id]);
        if($creditcard === []){
            return $this->view('Creditcard does note existe', 404);
        }
        if($this->getUser() !== null ) {
            $creditcard = $this->creditcardRepository->find($id);

            if ($creditcard->getCompany()->getMaster() === $this->getUser() || $this->MasterAdminDroit()) {
                $this->em->remove($creditcard);
                $this->em->flush();
            } else {
                return $this->view('Not the same user or tu n as pas les droits',401);
            }
        } else {
            return $this->view('Not Logged', 401);
        }
    }

}
