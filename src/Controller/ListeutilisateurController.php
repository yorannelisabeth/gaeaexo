<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface as EntityManager;


class ListeutilisateurController extends AbstractController
{
    /**
     * @Route("/listeutilisateur", name="utilisateur_ajouter")
     */
  
    public function index(): Response
    {
        return $this->render('listeutilisateur/index.html.twig');
    }
    /**
     * @Route("/listeutilisateur/ajouter", name="listeutilisateur_ajouter")
     */

    public function ajouter(Request $request, EntityManager $em)
    {
        if($request->isMethod("POST")){

            //recuperation des valeur
            $nom = $request->request->get("nom");
            $prenom = $request->request->get("prenom");
            $email = $request->request->get("email");
            $adresse = $request->request->get("adresse");
            $tel = $request->request->get("tel");


            $utilisateur = new Utilisateur;
            $utilisateur->setNom($nom);
            $utilisateur->setPrenom($prenom);
            $utilisateur->setEmail($email);
            $utilisateur->setAdresse($adresse);
            $utilisateur->setTel($tel);

            //preparation de la requète pour enregistrer en base de donné
            $em->persist($utilisateur);

            // executer la requète + mise a jour bdd
            $em->flush();

        }
        return $this->render("listeutilisateur/formulaire.html.twig");
    }

     /**
     * @Route("/listeutilisateur/voir", name="listeutilisateur_voir")
     */
    public function voir(UtilisateurRepository $utilisateurRepository):Response
    {
        $utilisateurs= $utilisateurRepository->findAll();
        return $this->render("listeutilisateur/index.html.twig",
            ["utilisateurs" =>$utilisateurs]
        );
    }
    

    /**
     * @Route("/listeutilisateur/supprimer", name="listeutilisateur_supprimer" , requirements:['id' => '\d+'])
     */
    //\d+ pour que le parametre nombre soit un entier de 1 ou plusieur chiffre
  
   

    public function supprimer(EntityManager $em, Request $request, Utilisateur $utilisateur)
    {
       
       if ($request->isMethod("POST")){
           // prépare la requète DELETE
           $em->remove($utilisateur);
           $em->flush();
         
       }
        return $this->render("listeutilisateur/supprimer.html.twig",["utilisateur" => $utilisateur]);
    }


}