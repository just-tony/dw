<?php

namespace App\Controller;

use App\Service\ApiConnector;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'main')]
    public function index(
        ApiConnector $apiConnector
    ): Response {
        return $this->render(
            'main/index.html.twig',
            [
                'balance' => $apiConnector->getUserBalance(1),
                'history' => $apiConnector->getHistory(1),
                'controller_name' => 'MainController',
            ]
        );
    }
}
