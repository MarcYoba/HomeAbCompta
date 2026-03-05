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
        $nbvemnteabgroup = count($vente);
        $vente = array_shift($vente); // Récupère la première vente (la plus récente)

        $sql = 'SELECT * FROM agence ORDER BY id DESC';
        $agence = $abgroup->executeQuery($sql)->fetchAllAssociative();
        $agence = array_shift($agence); // Récupère la première agence (la plus récente)

        $tnk = $em->getConnection('secondary');
        $bd+=1;
        $sql = 'SELECT * FROM vente ORDER BY id DESC';
        $tkn = $tnk->executeQuery($sql)->fetchAllAssociative();
        $nbtkn = count($tkn);
        $tkn = array_shift($tkn); // Récupère la première vente

        $sql = 'SELECT * FROM agence ORDER BY id DESC';
        $agencetnk = $tnk->executeQuery($sql)->fetchAllAssociative();
        $agencetnk = array_shift($agencetnk);
        
       //dd($vente);
        return $this->render('entreprise/index.html.twig', [
            'controller_name' => 'EntrepriseController',
            'vente' => $vente,
            'agence' => $agence,
            'bd' => $bd,
            'nbvente' => $nbvemnteabgroup,
            'nbtkn' => $nbtkn,
            'tkn' => $tkn,
            'agencetnk' => $agencetnk,
        ]);
    }
}
