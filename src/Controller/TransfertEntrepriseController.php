<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TransfertEntrepriseController extends AbstractController
{
    #[Route('/transfert/entreprise', name: 'app_transfert_entreprise')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $bd = 0;
        $abgroup = $doctrine->getConnection();
        $bd+=1;
        $date = new \DateTimeImmutable();

        $sql = 'SELECT * FROM agence ORDER BY id DESC';
        $agence = $abgroup->executeQuery($sql)->fetchAllAssociative();
        $agence = array_shift($agence); // Récupère la première agence (la plus récente)

        $tnk = $doctrine->getConnection('secondary');
        $bd+=1;

        $sql = 'SELECT * FROM agence ORDER BY id DESC';
        $agencetnk = $tnk->executeQuery($sql)->fetchAllAssociative();
        $agencetnk = array_shift($agencetnk);

        $rky = $doctrine->getConnection('Tertiary');
        $bd+=1;

        $sql = 'SELECT * FROM agence ORDER BY id DESC';
        $agencetriky = $rky->executeQuery($sql)->fetchAllAssociative();
        $agencetriky = array_shift($agencetriky);

        $katng = $doctrine->getConnection('Quaternary');
        $bd+=1;

        $sql = 'SELECT * FROM agence ORDER BY id DESC';
        $agencetkatang = $katng->executeQuery($sql)->fetchAllAssociative();
        $agencetkatang = array_shift($agencetkatang);


        return $this->render('transfert_entreprise/index.html.twig', [
            'AgenceAbgroup' => $agence,
            'AgenceTnk' => $agencetnk,
            'AgenceRky' => $agencetriky,
            'AgenceKatang' => $agencetkatang,
        ]);
    }
}
