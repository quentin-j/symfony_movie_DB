<?php

namespace App\Controller\Back;

use App\Entity\Genre;
use App\Form\GenreType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GenreController extends AbstractController
{
    /**
     * @Route("/back/genre", name="admin_genre_browse")
     */
    public function index(): Response
    {
        return $this->render('back/genre/browse.html.twig', [
            'controller_name' => 'GenreController',
        ]);
    }

       /**
     * @Route("/admin/genre/add", name="admin_genre_add")
     */
    public function add(Request $request): Response
    {
        $genre = new Genre();
        // Création d'un objet form type
        $form = $this->createForm(GenreType::class, $genre);

        $form->handleRequest($request); //<= cette méthode va vérifier si le formilaire html à été soumis en POST et si il est valide
        if ($form->isSubmitted() && $form->isValid())
        {
            // ici tout est ok
            $genre = $form->getData();
            // On enregistre
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($genre);
            $entityManager->flush();

            return $this->redirectToRoute('admin_genre_browse');
        }

        return $this->render('back/genre/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
