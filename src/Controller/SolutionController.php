<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SolutionController extends AbstractController
{
    #[Route('/solution/provenderie', name: 'app_solution_provenderie')]
    public function index(): Response
    {
        return $this->render('solution/provenderie.html.twig', [
            'controller_name' => 'SolutionController',
        ]);
    }

    #[Route('/solution/a/propos/de/nous', name: 'app_solution_propos')]
    public function propos(): Response
    {
        return $this->render('solution/propos.html.twig', [
            'controller_name' => 'SolutionController',
        ]);
    }

    #[Route('/solution/equipe', name: 'app_solution_equipe')]
    public function Equipe(): Response
    {
        return $this->render('solution/equipe.html.twig', [
            'controller_name' => 'SolutionController',
        ]);
    }
}
