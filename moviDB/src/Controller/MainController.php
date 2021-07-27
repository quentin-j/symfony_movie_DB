<?php

namespace App\Controller;

use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function homepage(MovieRepository $movieRepository): Response
    {
        // Afficher la liste de tous les films
        // $allMovies = $movieRepository->findAll() // <= on pourrait utiliser une variable intermédiare avant de passer la liste à la vue

        return $this->render('main/homepage.html.twig', [
            'movie_list' => $movieRepository->findAll(),
        ]);
    }
}
