<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use App\Form\UtilisateurType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface as EntityManager;
// use Symfony\Component\Form\FormTypeInterface;


class ListeutilisateurController extends AbstractController
{
    /**
     * @Route("/listeutilisateur", name="listeutilisateurs")
     */
  
    public function index(UtilisateurRepository $UtilisateurRepository): Response
    {
        $utilisateurs = $UtilisateurRepository->findAll();
        return $this->render('listeutilisateur/index.html.twig' , [
            'utilisateurs' => $utilisateurs
        ]);
    }
    /**
     * @Route("/listeutilisateur/nouveau", name="listeutilisateur_nouveau")
     */

    public function nouveau(Request $request, EntityManager $em)
    {

        $utilisateur = new Utilisateur;
        $formutilisateur = $this->createForm(UtilisateurType::class, $utilisateur);
        $formutilisateur->handleRequest($request);
        if($formutilisateur->isSubmitted()){
            $em->persist($utilisateur);
            $em->flush();
            return $this->redirectToRoute("listeutilisateurs");

           

        }
        return $this->render("listeutilisateur/form.html.twig", ["form" => $formutilisateur->createView()]);
    }
//\d+ pour que le parametre nombre soit un entier de 1 ou plusieur chiffre
     /**
     * @Route("/listeutilisateur/modifier/{id}", name="listeutilisateur_modifier" , requirements={"id"="\d+"})
     */
    public function modifier( EntityManager $em,Request $request , UtilisateurRepository $utilisateurRepository, $id){
        //find me permet de récuperer ce que on passe en paramètre tel que l'id
        $utilisateur = $utilisateurRepository->find($id);
        $formutilisateur = $this->createForm(UtilisateurType::class, $utilisateur);
        $formutilisateur->handleRequest($request);
        if($formutilisateur->isSubmitted()){
            // $em->persist($utilisateur); en gros on modifie donc pas besoin de l'utiliser car toutes les variable entités qui on un id non null vont ètre enregistré en bdd quand la methode flush sera appeller
            $em->flush();
            return $this->redirectToRoute("listeutilisateurs");

    }
    return $this->render("listeutilisateur/form.html.twig", ["form" => $formutilisateur->createView()]);
    

}

 /**
     * @Route("/listeutilisateur/supprimer/{id}", name="listeutilisateur_supprimer" , requirements={"id"="\d+"})
     */
    //en passant un objet utilisateur comme paramètre de la méthode sipprimer, $utilisateur sera récupéré dans la base de données selon la valeur {id} passé dans l'url de la route
    public function supprimer( EntityManager $em,Request $request , Utilisateur $utilisateur){
        if ($request->isMethod("POST")){
            //la methode 'remove()' prépare la requète DELETE
            $em->remove($utilisateur);
            $em->flush();
            return $this->redirectToRoute("listeutilisateurs");
        }
      
       return $this->render("listeutilisateur/supprimer.html.twig", ["utilisateur" => $utilisateur]);
      
     

    }

}


