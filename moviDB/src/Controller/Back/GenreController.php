<?php

namespace App\Controller\Back;

use App\Entity\Genre;
use App\Form\GenreType;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
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
        $allGenre = $genreRepo->findBy([], ['name' => 'ASC']);
        dump($allGenre);

        return $this->render('back/genre/browse.html.twig', [
            'genre_list' => $allGenre,
        ]);
    }

     /**
     * @Route("/admin/genre/{id}", name="admin_genre_read", requirements={"id"="\d+"}, methods={"GET", "POST"})
     */
    public function read(Genre $genre): Response
    {
        // ici on est sur d'avoir récupérer un objet car le ParamConverter renvoit une 404 dans le cas contraire
        return $this->render('back/genre/read.html.twig', [
            'genre' => $genre,
        ]);
    }

     /**
     * @Route("/admin/genre/edit/{id}", name="admin_genre_edit", methods={"GET", "POST"})
     */
    public function edit(Genre $genre, Request $request): Response    //<= Cette route est facultative, on l'a met ici car on fait du BREAD
    {

        $form = $this->createForm(GenreType::class, $genre);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            $genre->setUpdatedAt(new \DateTime());

            $em ->flush();

            $this->addFlash('success', 'Genre mis à jour avec success');

            return $this->redirectToRoute('admin_genre_browse');
        }

        return $this->render('back/genre/edit.html.twig', [
            'form' => $form->createView(),
            'genre' => $genre
        ]);
    }

       /**
     * @Route("/admin/genre/new", name="admin_genre_add", methods={"GET", "POST"})
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

            $this->addFlash('success', 'Genre ajouté avec success');

            return $this->redirectToRoute('admin_genre_browse');
        }

        return $this->render('back/genre/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

     /**
     * @Route("/admin/genre/delete/{id}", name="admin_genre_delete", methods={"GET"})
     */
    public function delete(Genre $genre, EntityManagerInterface $em): Response
    {
        $em->remove($genre);
        $em->flush();
        $this->addFlash('danger', '" ' . $genre->getName() . '" à était supprimé de la liste des genres !');
        return $this->redirectToRoute('admin_genre_browse');
    }
}
