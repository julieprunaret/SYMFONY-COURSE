<?php

namespace App\Controller;

use DateTime;
use App\Entity\Biens;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductPageController extends AbstractController
{
    #[Route('/bien/details/{id}', name: 'product')]
    public function details($id, ManagerRegistry $doctrine): Response
    {
        // Récupère l'objet en fonction de l'@Id (généralement appelé $id)
        $bien = $doctrine->getRepository(Biens::class)->find($id);
        // vérifier $bien avec le var dump => if($bien){var dump}else{erreur}
        return $this->render('product_page/details.html.twig', [
            'bien' => $bien
        ]);
    }

    #[Route('/bien/add', name: 'add_product')]
    public function add()
    {
        //etape 1 : à la création, on instancie une objet vide
        $bien = new Biens();
        //vous pouvez setter les valeurs par défaut de l'objet
        $bien->setCreatedAt(new DateTime()); //donne la date du jour

        // METHODE 1 : création du formulaire directement en controller
        $formBien = $this->createFormBuilder($bien)
        ->add('title', TextType::class)
        ->add('prix', IntegerType::class)
        ->add('description', TextAreaType::class)
        ->add('city', TextType::class)
        ->getForm();

        return $this->render('product_page/form-add.html.twig');
    }

}
