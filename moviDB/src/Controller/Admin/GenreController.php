<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GenreController extends AbstractController
{
    /**
     * @Route("/admin/genre", name="admin_genre")
     */
    public function index(): Response
    {
        return $this->render('admin/genre/index.html.twig', [
            'controller_name' => 'GenreController',
        ]);
    }

       /**
     * @Route("/admin/genre/add", name="admin_genre_add")
     */
    public function add(): Response
    {
        return $this->render('admin/genre/add.html.twig', [
            'controller_name' => 'GenreController',
        ]);
    }
}
