<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EntrepriseController extends AbstractController
{
    #[Route('/entreprise', name: 'app_entreprise')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $bd = 0;
        $abgroup = $doctrine->getConnection();
        $bd+=1;
        $date = new \DateTimeImmutable();

        $sql = 'SELECT * FROM vente_a ORDER BY id DESC';
        $vente = $abgroup->executeQuery($sql)->fetchAllAssociative();
        $nbvemnteabgroup = count($vente);
        $vente = array_shift($vente); // Récupère la première vente (la plus récente)
        $abinterval = $date->diff(new \DateTimeImmutable($vente['create_at']));
        $abinterval = $abinterval->format('%a jours');
        

        $sql = 'SELECT * FROM agence ORDER BY id DESC';
        $agence = $abgroup->executeQuery($sql)->fetchAllAssociative();
        $agence = array_shift($agence); // Récupère la première agence (la plus récente)

        $tnk = $doctrine->getConnection('secondary');
        $bd+=1;
        $sql = 'SELECT * FROM vente ORDER BY id DESC';
        $tkn = $tnk->executeQuery($sql)->fetchAllAssociative();
        $nbtkn = count($tkn);
        $tkn = array_shift($tkn); // Récupère la première vente
        $tkninterval = $date->diff(new \DateTimeImmutable($tkn['created_at']));
        $tkninterval = $tkninterval->format('%a jours');

        $sql = 'SELECT * FROM agence ORDER BY id DESC';
        $agencetnk = $tnk->executeQuery($sql)->fetchAllAssociative();
        $agencetnk = array_shift($agencetnk);

        $rky = $doctrine->getConnection('Tertiary');
        $bd+=1;
        
        $sql = 'SELECT * FROM vente ORDER BY id DESC';
        $riky = $rky->executeQuery($sql)->fetchAllAssociative();
        $nbriky = count($riky);
        $riky = array_shift($riky); // Récupère la première vente
        $rikyinterval = $date->diff(new \DateTimeImmutable($riky['created_at']));
        $rikyinterval = $rikyinterval->format('%a jours');


        $sql = 'SELECT * FROM agence ORDER BY id DESC';
        $agencetriky = $rky->executeQuery($sql)->fetchAllAssociative();
        $agencetriky = array_shift($agencetriky);

        $katng = $doctrine->getConnection('Quaternary');
        $bd+=1;

        $sql = 'SELECT * FROM vente_a ORDER BY id DESC';
        $ventekatng = $katng->executeQuery($sql)->fetchAllAssociative();
        $nbvemntekatng = count($ventekatng);
        $katanga = array_shift($ventekatng); // Récupère la première vente (la plus récente)
        $ktinterval = $date->diff(new \DateTimeImmutable($katanga['create_at']));
        $ktinterval = $ktinterval->format('%a jours');

        $sql = 'SELECT * FROM agence ORDER BY id DESC';
        $agencetkatang = $katng->executeQuery($sql)->fetchAllAssociative();
        $agencetkatang = array_shift($agencetkatang);


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
            'abinterval' => $abinterval,
            'tkninterval' => $tkninterval,

            'riky' => $riky,
            'agencetriky' => $agencetriky,
            'nbriky' => $nbriky,
            'rikyinterval' => $rikyinterval,

            'katanga' => $katanga,
            'agencetkatang' => $agencetkatang,
            'nbvemntekatng' => $nbvemntekatng,
            'ktinterval' => $ktinterval,
        ]);
    }
}
