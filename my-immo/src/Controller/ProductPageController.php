<?php

namespace App\Controller;

use DateTime;
use App\Entity\Biens;
use App\Form\FormBienType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductPageController extends AbstractController
{
    #[Route('/bien/details/{id}', name: 'product')]
    public function details($id, ManagerRegistry $doctrine): Response
    {
        // Récupère l'objet en fonction de l'@Id (généralement appelé $id)
        $bien = $doctrine->getRepository(Biens::class)->find($id);
        // vérifier $bien avec le var dump => if($bien){var dump}else{erreur}
        return $this->render('product_page/details.html.twig', [ //le render envoit l'affichage
            'bien' => $bien
        ]);
    }

    #[Route('/bien/add', name: 'add_product')] 
    /* 
    *le premier url est celui que l'on défini pour la page concernée.
    *le name est le nom que l'on donne à la page (son id, qui sera dans le path des liens)
    */
    public function add(Request $request, ManagerRegistry $doctrine)
    {
        
        $bien = new Biens();//etape 1 : à la création, on instancie une objet vide
        $bien->setCreatedAt(new DateTime()); //ici on donne les champs remplis par défaut, ils n'apparaitront pas dans le formulaire => donne la date du jour => ce champs sera rempli automatiquement


        // METHODE 1 (FORM BUILDER) : création du formulaire directement en controller


        // $formBien = $this->createFormBuilder($bien) //on crée un formulaire à la main (sans la CLI)
        // ->add('title', TextType::class)//on donne le nom du champs puis son type en HTML (et donc text pas string), on peut mettre les contraintes ici en 3em parametre (pour les passwords et email par exemple)
        // ->add('prix', IntegerType::class)
        // ->add('description', TextAreaType::class)
        // ->add('city', TextType::class)
        // //->add('save', SubmitType::class, ['label' => 'Enregistrer'] )//dans le cadre d'un formulaire personnalisé on gère le input dans la view
        // ->getForm(); //on récupère le formulaire après avoir nommé ses champs



        
        // METHODE 2 (FORM BUILDER) : création du formulaire avec la commande
        $formBien = $this->createForm(FormBienType::class, $bien); //tout est géré dans le form

        $formBien->handleRequest($request); //on récupère la requete
        
        //on teste ensuite si le formulaire 1) a été rempli et soumi et 2) s'il est valide
        if($formBien->isSubmitted() && $formBien->isValid())
        {
            //l'entité manager de doctrine vous permettra d'enregistrer les données en bdd
            $entityManager = $doctrine->getManager();

            $entityManager->persist($bien);//on enregistre de nouvelles données
            $entityManager->flush();

            //pour ajouter un message dans le feedback; dans la variable 'flash'
            $this->addFlash(
                'success_add', 
                'Le bien a été ajouté !'
            );

            return $this->redirectToRoute('app_home');//on peut envoyer à la fin sur home ou une autre page
        }


        //méthod render 1
        return $this->render('product_page/form-add.html.twig', [ //le render envoit l'affichage
            'formBien' => $formBien->createView()
        ]);// ici on crée la vue du formulaire

        //méthode render 2 (nouvelle)
        // return $this->renderForm('product_page/form-add.html.twig', [
        //     'formBien' => $formBien
        // ]);

    }
}