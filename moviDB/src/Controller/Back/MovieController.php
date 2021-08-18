<?php

namespace App\Controller\Back;

use App\Entity\Movie;
use App\Form\MovieType;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    /**
     * @Route("/admin/movie", name="admin_movie_browse", methods="GET")
     */
    public function browse(MovieRepository $movieRepo): Response
    {
        $allMovie = $movieRepo->findBy([], ['title' => 'ASC']);
        dump($allMovie);

        return $this->render('back/movie/browse.html.twig', [
            'movie_list' => $allMovie,
        ]);
    }

     /**
     * @Route("/admin/movie/{id}", name="admin_movie_read", requirements={"id"="\d+"}, methods={"GET", "POST"})
     */
    public function read(Movie $movie): Response
    {
        // ici on est sur d'avoir récupérer un objet car le ParamConverter renvoit une 404 dans le cas contraire
        return $this->render('back/movie/read.html.twig', [
            'movie' => $movie,
        ]);
    }

     /**
     * @Route("/admin/movie/edit/{id}", name="admin_movie_edit", methods={"GET", "POST"})
     */
    public function edit(Movie $movie, Request $request): Response    //<= Cette route est facultative, on l'a met ici car on fait du BREAD
    {

        $form = $this->createForm(MovieType::class, $movie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            $movie->setUpdatedAt(new \DateTime());

            $em ->flush();

            // TODO flash message

            return $this->redirectToRoute('admin_movie_browse');
        }

        return $this->render('back/movie/edit.html.twig', [
            'form' => $form->createView(),
            'movie' => $movie
        ]);
    }

       /**
     * @Route("/admin/movie/new", name="admin_movie_add", methods={"GET", "POST"})
     */
    public function add(Request $request): Response
    {
        $movie = new Movie();
        // Création d'un objet form type
        $form = $this->createForm(MovieType::class, $movie);

        $form->handleRequest($request); //<= cette méthode va vérifier si le formilaire html à été soumis en POST et si il est valide
        if ($form->isSubmitted() && $form->isValid())
        {
            // ici tout est ok
            $movie = $form->getData();
            // On enregistre
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($movie);
            $entityManager->flush();

            return $this->redirectToRoute('admin_movie_browse');
        }

        return $this->render('back/movie/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

     /**
     * @Route("/admin/movie/delete/{id}", name="admin_movie_delete", methods={"GET"})
     */
    public function delete(Movie $movie, EntityManagerInterface $em): Response
    {
        $em->remove($movie);
        $em->flush();
        return $this->redirectToRoute('admin_movie_browse');
    }
}
