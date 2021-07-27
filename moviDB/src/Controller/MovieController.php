<?php

namespace App\Controller;

use App\Entity\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    /**
     * @Route("/movie/{id}", name="movie_show", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function show($id, Movie $movie): Response
    {
        return $this->render('movie/show.html.twig', [
            "movie" => $movie,
        ]);
    }
}
