<?php

namespace App\Controller;

use App\Entity\Biens;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ManagerRegistry $doctrine): Response
    {
        //$biens = $doctrine->getRepository(Biens::class)->findAll();
        $biens = $doctrine->getRepository(Biens::class)->findBy([], ['id' => 'DESC']);
        return $this->render('home/index.html.twig', [
            'biens' => $biens 
        ]);
    }
}
