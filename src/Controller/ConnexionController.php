<?php

namespace App\Controller;

use App\Entity\Joueur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\DBAL\Connection;    // Pour avoir accès à l'engin de query

ini_set("date.timezone", "America/New_York");

header('Access-Control-Allow-Origin: *');

class ConnexionController extends AbstractController
{
    //-------------------------------------
    //
    //-------------------------------------
    #[Route('/getJoueurs')]
    public function getJoueurs(Connection $connexion): JsonResponse
    {
        $joueurs = $connexion->FetchAllAssociative('select * from joueur');
        return $this->json($joueurs);
    }


    //-------------------------------------
    //
    //-------------------------------------
    #[Route('/creationCompte', name: 'app_creationCompte')]
    public function creationCompte(Request $req, EntityManagerInterface $entityManager): JsonResponse
    {
        $nom = $req->request->get("nom");
        $mdp = $req->request->get("mdp");
        $courriel = $req->request->get("courriel");

        if ($this->infoValides($nom, $mdp, $courriel)) {
            $creation = new \DateTime();
            $nbLogin = 1;

            if ($req->getMethod() == "POST") {
                $j = new Joueur();
                $j->setNom($nom);
                $j->setMotDePasse($mdp);
                $j->setCourriel($courriel);
                $j->setNbLogin($nbLogin);
                $j->setCreation($creation);
                $j->setDernierLogin($creation);

                $entityManager->persist($j);
                $entityManager->flush();

                $retJoueur["id"] = $j->getId();
                $retJoueur["nom"] = $j->getNom();
                $retJoueur["courriel"] = $j->getCourriel();

                return $this->json($retJoueur);
            } else {
                return $this->json("erreur 62");
            }
        } else {
            return $this->json("erreur 66");
        }
    }


    //-------------------------------------
    //
    //-------------------------------------
    private function infoValides($nom, $mdp, $courriel)
    {
        return true;
    }


    //-------------------------------------
    //
    //-------------------------------------
    public function connexion(Request $req, EntityManagerInterface $entityManager, Connection $connexion): JsonResponse
    {
        $nom = $req->request->get("nom");
        $mdp = $req->request->get("mdp");

        $joueur = $connexion->FetchAllAssociative("SELECT * FROM joueur WHERE nom = '$nom'");

        if (isset($joueur[0])) {
            if ($joueur[0]["motDePasse"] === $mdp) {
                $retJoueur["id"] = $joueur[0]["id"];
                $retJoueur["nom"] = $joueur[0]["nom"];
                $retJoueur["courriel"] = $joueur[0]["courriel"];
                return $this->json($retJoueur);
            } else {
                return $this->json("erreur 112");
            }
        } else {
            return $this->json("erreur 117");
        }
    }
}
