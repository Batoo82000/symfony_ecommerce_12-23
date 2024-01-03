<?php

namespace App\Controller;

use App\Repository\SettingRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    // Cette annotation Symfony déclare une route pour l'URL '/' avec le nom 'app_home'.
    // Lorsque quelqu'un accède à l'URL '/', la méthode 'index' de ce contrôleur sera appelée.
    public function index(SettingRepository $settingRepository, Request $request): Response
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
        // Rend la vue associée à ce contrôleur. Ici, 'home/index.html.twig'.
        // Les données passées à la vue incluent le nom du contrôleur ('HomeController').
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}