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
use Dompdf\Dompdf;
use Dompdf\Options;

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
     /**
     * @Route("/listeutilisateur/data", name="listeutilisateur_data")
     */
    public function listeutilisateurData(UtilisateurRepository $UtilisateurRepository):Response
    {
        $utilisateurs = $UtilisateurRepository->findAll();
        return $this->render('listeutilisateur/download.html.twig' , [
            'utilisateurs' => $utilisateurs
        ]);
        return $this->render('listeutilisateur/data.html.twig');
    }
    
 /**
     * @Route("/listeutilisateur/data/download", name="listeutilisateur_download")
     * 
     */

     public function downloadUserData(UtilisateurRepository $UtilisateurRepository):Response
     {


        $utilisateurs = $UtilisateurRepository->findAll();
        return $this->render('listeutilisateur/download.html.twig' , [
            'utilisateurs' => $utilisateurs
        ]);


         //installation de DOMpdf : composer require dompdf/dompdf (dans le terminal)

         //on defini les option du pdf
         $pdfOptions = new Options();
         //police par defauts
         $pdfOptions->set('defaultFont', 'Arial');
         //resoudre pb lié au sll
         $pdfOptions->setIsRemoteEnabled(true);
         // on instancie Dompdf

         $dompdf = new Dompdf($pdfOptions);
         $context = stream_context_create([
             'ssl'=>[
                 'verify_peer' => FALSE,
                 'verify_peer_name'=> FALSE,
                 'allow_self_signed'=> TRUE

             ]
             ]);
             $dompdf-> setHttpContext($context);

             //on genère le hrtml

             $html = $this->renderView('listeutilisateur/download.html.twig');
             $dompdf->loadHtml($html);
             $dompdf->setPaper('A4', 'portrait');
             $dompdf->render();

             //ongénère un nom de fichier
             $fichier = 'utilisateur-data'.'.pdf';

             // on envoie le pdf au navigateur

             $dompdf->stream($fichier, [
                 'Attachment' => true
             ]);
             return new Response();

            
     }

}


