<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CreditcardController extends AbstractController
{
    /**
     * @Route("/creditcard", name="creditcard")
     */
    public function index()
    {
        return $this->render('creditcard/index.html.twig', [
            'controller_name' => 'CreditcardController',
        ]);
    }
}
