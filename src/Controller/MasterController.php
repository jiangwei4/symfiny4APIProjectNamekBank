<?php

namespace App\Controller;

use App\Entity\Master;
use App\Repository\MasterRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Swagger\Annotations as SWG;

class MasterController extends FOSRestController
{
    private $em;
    private $masterRepository;
    public function __construct(MasterRepository $masterRepository, EntityManagerInterface $em)
    {
        $this->masterRepository = $masterRepository;
        $this->em = $em;

    }

  /*  private function testMaster(Master $master)
    {
        if ($this->getUser() === $master || in_array("ROLE_ADMIN",$this->getUser()->getRoles()) ) {
            $return = true;
        } else {
            $return = false;
        }
        return $return;
    }*/
 /*   private function testMasterDroit()
    {
        if (in_array("ROLE_ADMIN",$this->get) ) {
            $return = true;
        } else {
            $return = false;
        }
        return $return;
    }
*/

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
        dump($this->getUser());die;
     /*   if($this->getUser() !== null )
        {
            if ($this->testMasterDroit()) {*/
                return $this->view($this->masterRepository->findAll());
        /*    }
            return $this->view('Not Logged for this user or not an Admin', 401);
        } else {
            return $this->view('Not Logged', 401);
        }*/
    }


}
