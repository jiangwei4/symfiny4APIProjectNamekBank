<?php

namespace App\Controller;

use App\Entity\Master;
use App\Repository\MasterRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class MasterController extends FOSRestController
{
    private $em;
    private $masterRepository;
    public function __construct(MasterRepository $masterRepository, EntityManagerInterface $em)
    {
        $this->masterRepository = $masterRepository;
        $this->em = $em;

    }

    private function MasterDroitMaster(Master $master)
    {
        if ($this->getUser() === $master || in_array("ROLE_ADMIN",$this->getUser()->getRoles()) ) {
            $return = true;
        } else {
            $return = false;
        }
        return $return;
    }
    private function MasterDroit()
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
     * @SWG\Tag(name="master")
     * @Rest\View(serializerGroups={"master"})
     */
    public function getMastersAction()
    {

        if($this->getUser() !== null )
        {
            if ($this->MasterDroit()) {
                return $this->view($this->masterRepository->findAll());
            }
            return $this->view('Not Logged for this user or not an Admin', 403);
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
     * @SWG\Tag(name="master")
     * @Rest\View(serializerGroups={"master"})
     *
     */
    public function getUserAction(Master $master)
    {
        if($this->getUser() !== null ) {
            if ($this->MasterDroitMaster($master)) {

                return $this->view($master);
            }
            return $this->view('Not Logged for this user or not an Admin', 403);
        } else {
            return $this->view('Not Logged', 401);
        }
    }












    /**
     * @SWG\Response(response=200, description="")
     * @SWG\Tag(name="master")
     * @Rest\View(serializerGroups={"master"})
     * @Rest\Post("/masters")
     * @ParamConverter("user", converter="fos_rest.request_body")
     */
    public function postUsersAction(Master $master, EntityManagerInterface $em, ValidatorInterface $validator)
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
    public function putUserAction(Request $request, $id, ValidatorInterface $validator)
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
    public function deleteUserAction($id)
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