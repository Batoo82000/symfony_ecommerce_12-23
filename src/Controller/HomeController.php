<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\PagesRepository;
use App\Repository\ProductRepository;
use App\Repository\SettingRepository;
use App\Repository\SlidersRepository;
use App\Repository\CategoryRepository;
use App\Repository\CollectionsRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    #[Route('/', name: 'app_home')]
    // Cette annotation Symfony déclare une route pour l'URL '/' avec le nom 'app_home'.
    // Lorsque quelqu'un accède à l'URL '/', la méthode 'index' de ce contrôleur sera appelée.
    public function index(
        SettingRepository $settingRepository,
        SlidersRepository $slidersRepository, 
        CollectionsRepository $collectionsRepository,
        CategoryRepository $categoryRepository,
        PagesRepository $pagesRepository,
        Request $request): Response
    {
        // La méthode 'index' prend deux arguments :
        // - $settingRepository : Une instance de la classe SettingRepository, qui est utilisée pour interagir avec la base de données et récupérer des données.
        // - $request : Une instance de la classe Request, qui contient des informations sur la requête HTTP actuelle.

        // Récupère la session en cours à partir de la requête.
        $session = $request->getSession();

        // Utilise le repository pour récupérer toutes les données de l'entité Setting.
        $data = $settingRepository->findAll();
        // Stocke la première entrée des données dans la session sous la clé 'setting'.
        $session->set("setting", $data[0]);
        
        // Utilise le repository pour récupérer toutes les données de l'entité Sliders.
        $sliders = $slidersRepository->findAll();

        // Utilise le repository pour récupérer toutes les données de l'entité Sliders.
        $collections = $collectionsRepository->findAll();
        // shuffle me permet de mélanger les données du tableau $collections
        shuffle($collections);
        // array_slice extrait dans ce cas précis les 2 premiers éléments du tableau $collections, le but étant d'afficher que 2 éléments dans ma vue twig
        $selectedCollections = array_slice($collections, 0, 2);
        
        // Utilise le repository pour récupérer les données qui sont à true de isHeader et isFooter.
        $pagesHeader = $pagesRepository->findBy(['isHeader' => true]);
        $pagesFooter = $pagesRepository->findBy(['isFooter' => true]);
        // Stocke les pages ayant isHeader à true dans la session sous la clé 'pagesHeader'.
        $session->set("pagesHeader", $pagesHeader);
        // Stocke les pages ayant isFooter à true dans la session sous la clé 'pagesFooter'.
        $session->set("pagesFooter", $pagesFooter);
        
        // Stocke les pages ayant isMega à true dans la session sous la clé 'categoriesMega'.
        $categoriesMega = $categoryRepository->findBy(['isMega' => true]);

        $session->set("categoriesMega", $categoriesMega);

        // Récupère les produits avec les valeurs booléennes correspondantes à true
        $productsBestSeller = $this->productRepository->findBy(['isBestSeller' => true]);
        $productsNewArrival = $this->productRepository->findBy(['isNewArrival' => true]);
        $productsFeatured = $this->productRepository->findBy(['isFeatured' => true]);
        $productsSpecialOffer = $this->productRepository->findBy(['IsSpecialOffer' => true]);


        // Rend la vue associée à ce contrôleur. Ici, 'home/index.html.twig'.
        // Les données passées à la vue incluent le nom du contrôleur ('HomeController'), les données de $sliders, $selectedCollections, et des produits.
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'sliders' => $sliders,
            'collections' => $selectedCollections,
            'productsBestSeller' => $productsBestSeller,
            'productsNewArrival'=> $productsNewArrival,
            'productsFeatured' => $productsFeatured,
            'productsSpecialOffer' => $productsSpecialOffer,
        ]);
    }
}