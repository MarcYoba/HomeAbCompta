<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EntrepriseController extends AbstractController
{
    #[Route('/entreprise', name: 'app_entreprise')]
    public function index(EntityManagerInterface $em): Response
    {
        $bd = 0;
        $abgroup = $em->getConnection();
        $bd+=1;

        $sql = 'SELECT * FROM vente_a ORDER BY id DESC';
        $vente = $abgroup->executeQuery($sql)->fetchAllAssociative();
        $nbvemnte = count($vente);
        $vente = array_pop($vente); // Récupère la première vente (la plus récente)
        $sql = 'SELECT * FROM agence ORDER BY id DESC';
        $agence = $abgroup->executeQuery($sql)->fetchAllAssociative();
        $agence = array_pop($agence); // Récupère la première agence (la plus récente)
       //dd($vente);
        return $this->render('entreprise/index.html.twig', [
            'controller_name' => 'EntrepriseController',
            'vente' => $vente,
            'agence' => $agence,
            'bd' => $bd,
            'nbvente' => $nbvemnte,
        ]);
    }
}
