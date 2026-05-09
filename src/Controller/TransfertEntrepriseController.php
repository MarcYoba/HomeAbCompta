<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/tranfert/entreprise/katanga', name: 'app_transfert_entreprise_katanga')]
    public function katanga(ManagerRegistry $doctrine, Request $request): Response
    {        
        $abgroup = $doctrine->getConnection();
        $sql = 'SELECT * FROM agence ORDER BY id DESC';
        $agence = $abgroup->executeQuery($sql)->fetchAllAssociative();
        $agence = array_shift($agence); // Récupère la première agence (la plus récente)

        $sql = 'SELECT * FROM employer ORDER BY id DESC';
        $employer = $abgroup->executeQuery($sql)->fetchAllAssociative();

        $sql = 'SELECT * FROM magasin_acentrale ORDER BY id DESC';
        $produitmagasin = $abgroup->executeQuery($sql)->fetchAllAssociative();
        $produitdirestion = [];
        foreach ($produitmagasin as $key => $value) {
            $sql = 'SELECT * FROM produit_a WHERE id = :id';
            $tmp = $abgroup->executeQuery($sql, ['id' => $value['produit_id']])->fetchAllAssociative();
            array_push($produitdirestion,['id' => $tmp['0']['id'], 'nom' => $tmp['0']['nom']]);
        }

        $katng = $doctrine->getConnection('Quaternary');

        $sql = 'SELECT * FROM agence ORDER BY id DESC';
        $agencetkatang = $katng->executeQuery($sql)->fetchAllAssociative();
        $agencetkatang = array_shift($agencetkatang);

        $sql = 'SELECT * FROM employer ORDER BY id DESC';
        $employerkatng = $katng->executeQuery($sql)->fetchAllAssociative();

        $sql = 'SELECT * FROM produit_a ORDER BY id DESC';
        $produitkatng = $katng->executeQuery($sql)->fetchAllAssociative();

        $numero = str_pad(random_int(0, 99), 3, '0', STR_PAD_LEFT);
        $datePart = date('Ymd');
        $lettres = chr(random_int(65, 90)) . chr(random_int(65, 90));
            
        $matricule = $numero . $datePart . $lettres;

        if ($request->isMethod('POST')) {
            $tableau = $request->request->all();
            $matricules = $request->request->get('matricule');
            $date = $request->request->get('date');
            $direction = $request->request->get('direction');
            $agences = $request->request->get('agence');
            $employers = $request->request->get('employer');
            $produitdirection = $request->request->get('produitdirection');
            $produitkatngs = $request->request->get('produitkatng');
            $quantite = $request->request->get('produit');

            $sql = 'SELECT * FROM produit_a where id = :id';
            $equivalent = $abgroup->executeQuery($sql, ['id' => $produitdirection])->fetchAssociative();

            $sql = 'SELECT * FROM magasin_acentrale WHERE produit_id = :id';
            $produitdirection = $abgroup->executeQuery($sql, ['id' => $produitdirection])->fetchAssociative();

            
            $json = [
                'id' => $equivalent['id'],
                'nom' => $equivalent['nom']
            ];

            $reste = $produitdirection['quantite'] - $quantite;

            $sql = 'update magasin_acentrale set quantite = :quantite where id = :id';
            $abgroup->executeQuery($sql, ['quantite' => $reste, 'id' => $produitdirection['id']]);

            // Récupérer les détails de l'employé sélectionné
            // 1. Définir la requête SQL d'insertion
            $sql = "INSERT INTO transfert_adirection (produit_id, user_id, quantite, reste, createt_at, statut, matricule, origine, destination, equivalent) VALUES (:produit_id, :user_id, :quantite, :reste, :createt_at, :statut, :matricule, :origine, :destination, :equivalent)";

            // 2. Préparer les données à insérer
            $donnees = [
                'produit_id' => $produitkatngs,
                'user_id' => $employers,
                'quantite' => $quantite,
                'reste' => $reste,
                'createt_at' => $date,
                'statut' => "Attente",
                'matricule' => $matricules,
                'origine' => $direction,
                'destination' => $agences,
                'equivalent' => json_encode($json),
            ];
            // 3. Exécuter la requête
            $katng->executeQuery($sql, $donnees);
            $this->addFlash('success', 'Transfert effectué avec succès !');
            $this->redirectToRoute('app_transfert_entreprise_katanga');
            // Traitez les données comme nécessaire (par exemple, enregistrez le transfert dans la base de données)
        }

        return $this->render('transfert_entreprise/katanga.html.twig', [
            'Agencedirection' => $agence,
            'matricule' => $matricule,
            'employers' => $employer,
            'employerkatng' => $employerkatng,
            'AgenceKatang' => $agencetkatang,
            'produitdirestion' => $produitdirestion,
            'produitkatng' => $produitkatng,
        ]);
    }

    #[Route('/tranfert/entreprise/abgroupsarl', name: 'app_transfert_entreprise_abgroupsarl')]
    public function abgroupsarl(ManagerRegistry $doctrine, Request $request): Response
    {        
        $abgroup = $doctrine->getConnection();
        $sql = 'SELECT * FROM agence ORDER BY id DESC';
        $agence = $abgroup->executeQuery($sql)->fetchAllAssociative();
        $agence = array_shift($agence); // Récupère la première agence (la plus récente)

        $sql = 'SELECT * FROM employer ORDER BY id DESC';
        $employer = $abgroup->executeQuery($sql)->fetchAllAssociative();

        $sql = 'SELECT * FROM magasin_acentrale ORDER BY id DESC';
        $produitmagasin = $abgroup->executeQuery($sql)->fetchAllAssociative();
        $produitdirestion = [];
        foreach ($produitmagasin as $key => $value) {
            $sql = 'SELECT * FROM produit_a WHERE id = :id';
            $tmp = $abgroup->executeQuery($sql, ['id' => $value['produit_id']])->fetchAllAssociative();
            array_push($produitdirestion,['id' => $tmp['0']['id'], 'nom' => $tmp['0']['nom']]);
        }


        $sql = 'SELECT * FROM agence ORDER BY id DESC';
        $agencetkatang = $abgroup->executeQuery($sql)->fetchAllAssociative();
        $agencetkatang = array_shift($agencetkatang);

        $sql = 'SELECT * FROM employer ORDER BY id DESC';
        $employerkatng = $abgroup->executeQuery($sql)->fetchAllAssociative();

        $sql = 'SELECT * FROM produit_a ORDER BY id DESC';
        $produitkatng = $abgroup->executeQuery($sql)->fetchAllAssociative();

        $numero = str_pad(random_int(0, 99), 3, '0', STR_PAD_LEFT);
        $datePart = date('Ymd');
        $lettres = chr(random_int(65, 90)) . chr(random_int(65, 90));
            
        $matricule = $numero . $datePart . $lettres;

        if ($request->isMethod('POST')) {
            $tableau = $request->request->all();
            $matricules = $request->request->get('matricule');
            $date = $request->request->get('date');
            $direction = $request->request->get('direction');
            $agences = $request->request->get('agence');
            $employers = $request->request->get('employer');
            $produitdirection = $request->request->get('produitdirection');
            $produitkatngs = $request->request->get('produitkatng');
            $quantite = $request->request->get('produit');

            $sql = 'SELECT * FROM produit_a where id = :id';
            $equivalent = $abgroup->executeQuery($sql, ['id' => $produitdirection])->fetchAssociative();

            $sql = 'SELECT * FROM magasin_acentrale WHERE produit_id = :id';
            $produitdirection = $abgroup->executeQuery($sql, ['id' => $produitdirection])->fetchAssociative();

            
            $json = [
                'id' => $equivalent['id'],
                'nom' => $equivalent['nom']
            ];

            $reste = $produitdirection['quantite'] - $quantite;

            $sql = 'update magasin_acentrale set quantite = :quantite where id = :id';
            $abgroup->executeQuery($sql, ['quantite' => $reste, 'id' => $produitdirection['id']]);

            // Récupérer les détails de l'employé sélectionné
            // 1. Définir la requête SQL d'insertion
            $sql = "INSERT INTO transfert_adirection (produit_id, user_id, quantite, reste, createt_at, statut, matricule, origine, destination, equivalent) VALUES (:produit_id, :user_id, :quantite, :reste, :createt_at, :statut, :matricule, :origine, :destination, :equivalent)";

            // 2. Préparer les données à insérer
            $donnees = [
                'produit_id' => $produitkatngs,
                'user_id' => $employers,
                'quantite' => $quantite,
                'reste' => $reste,
                'createt_at' => $date,
                'statut' => "Attente",
                'matricule' => $matricules,
                'origine' => $direction,
                'destination' => $agences,
                'equivalent' => json_encode($json),
            ];
            // 3. Exécuter la requête
            $abgroup->executeQuery($sql, $donnees);
            $this->addFlash('success', 'Transfert effectué avec succès Abgroup sarl!');
            $this->redirectToRoute('app_transfert_entreprise_katanga');
            // Traitez les données comme nécessaire (par exemple, enregistrez le transfert dans la base de données)
        }

        return $this->render('transfert_entreprise/abgroup.html.twig', [
            'Agencedirection' => $agence,
            'matricule' => $matricule,
            'employers' => $employer,
            'employerkatng' => $employerkatng,
            'AgenceKatang' => $agencetkatang,
            'produitdirestion' => $produitdirestion,
            'produitkatng' => $produitkatng,
        ]);
    }
}
