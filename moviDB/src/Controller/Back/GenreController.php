<?php

namespace App\Controller\Back;

use App\Entity\Genre;
use App\Form\GenreType;
use App\Repository\GenreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GenreController extends AbstractController
{
    /**
     * @Route("/admin/genre", name="admin_genre_browse", methods="GET")
     */
    public function browse(GenreRepository $genreRepo): Response
    {
        $allGenre = $genreRepo->findAll();
        dump($allGenre);

        return $this->render('back/genre/browse.html.twig', [
            'genre_list' => $allGenre,
        ]);
    }

     /**
     * @Route("/admin/genre/{id}", name="admin_genre_read", methods={"GET", "POST"})
     */
    public function read(): Response
    {
        return $this->render('back/genre/read.html.twig', [
            'controller_name' => 'GenreController',
        ]);
    }

     /**
     * @Route("/admin/genre/edit/{id}", name="admin_genre_edit", methods={"GET"})
     */
    public function edit(): Response    //<= Cette route est facultative, on l'a met ici car on fait du BREAD
    {
        return $this->render('back/genre/edit.html.twig', [
            'controller_name' => 'GenreController',
        ]);
    }

       /**
     * @Route("/admin/genre/add", name="admin_genre_add", methods={"GET", "POST"})
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

     /**
     * @Route("/admin/genre/delete/{id}", name="admin_genre_delete", methods={"GET"})
     */
    public function delete(): Response
    {
        return $this->render('back/genre/browse.html.twig', [
            'controller_name' => 'GenreController',
        ]);
    }
}
