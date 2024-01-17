<?php

namespace App\Controller;

use App\Repository\PagesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PagesController extends AbstractController
{
    #[Route('/pages/{slug}', name: 'app_pages')]
    public function index(string $slug, PagesRepository $pagesRepository): Response
    {
        $pages = $pagesRepository->findOneBy(["slug"=>$slug]);


        return $this->render('pages/index.html.twig', [
            'controller_name' => 'PagesController',
            'pages' => $pages,
        ]);
    }
}
