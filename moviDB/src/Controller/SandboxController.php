<?php

namespace App\Controller;

use App\Entity\Casting;
use App\Entity\Genre;
use App\Entity\Movie;
use App\Entity\Person;
use App\Repository\GenreRepository;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SandboxController extends AbstractController
{
    /**
     * @Route("/sandbox/genres", name="sandbox_genres")
     */
    public function demoGenres(GenreRepository $repo): Response 
    {
        $genres = $repo->findGenreDemo();

        dump($genres);

        return $this->render('sandbox/index.html.twig', [
            'controller_name' => 'SandboxController',
        ]);
    }

     /**
     * @Route("/sandbox/movies", name="sandbox_movies")
     */
    public function demoMovies(MovieRepository $repo): Response
    {
        $movies = $repo->findAllOrdered();

        dump($movies);

        return $this->render('sandbox/index.html.twig', [
            'controller_name' => 'SandboxController',
        ]);
    }

    /**
     * @Route("/sandbox/db_init", name="sandbox_init")
     */
    public function db_init(EntityManagerInterface $entityManager): Response    //<= 1er façon de récupérer l'entityManager
    {
        // $entityManager = $this->getDoctrine()->getManager(); // <= 2eme façon de récupèrer l'entityManager
        // Création de genres
        $genreAction = new Genre();
        $genreAction->setName('Action');
        $entityManager->persist($genreAction);

        $genreHorreur = new Genre();
        $genreHorreur->setName('Horreur');
        $entityManager->persist($genreHorreur);

        $genreDrame = new Genre();
        $genreDrame->setName('Drame');
        $entityManager->persist($genreDrame);  
        
        $genreThriller = new Genre();
        $genreThriller->setName('Thriller');
        $entityManager->persist($genreThriller); 
        
        $genreComedie = new Genre();
        $genreComedie->setName('Comédie');
        $entityManager->persist($genreComedie); 

        $genreScienceFiction = new Genre();
        $genreScienceFiction->setName('Science Fiction');
        $entityManager->persist($genreScienceFiction); 


        //création de movie
        $movieBackToFuture = new Movie();
        $movieBackToFuture->setTitle('Back To Future');
        $movieBackToFuture->addGenre($genreScienceFiction)
            ->addGenre($genreComedie);
        $entityManager->persist($movieBackToFuture);

        $movieFargo = new Movie();
        $movieFargo->setTitle('Fargo');
        $movieFargo->addGenre($genreThriller);
        $entityManager->persist($movieFargo);
        
        $movieGodzilla = new Movie();
        $movieGodzilla->setTitle('Godzilla');
        $movieGodzilla->addGenre($genreAction) // <= Comme la méthode addGenre renvoie $this (c'est à dire movieGodzilla) on peut "chainer" les méthode en PHP
            ->addGenre($genreScienceFiction)
            ->addGenre($genreDrame);
        $entityManager->persist($movieGodzilla);

        // Création des personnes
        $personMacy = new Person();
        $personMacy->setName('William H Macy');
        $entityManager->persist($personMacy);

        $personReno = new Person();
        $personReno->setName('Jean Reno');
        $entityManager->persist($personReno);

        $personLloyd = new Person();
        $personLloyd->setName('Christopher Lloyd');
        $entityManager->persist($personLloyd);

        $personFox = new Person();
        $personFox->setName('Michael J Fox');
        $entityManager->persist($personFox);
        
        $personDenver = new Person();
        $personDenver->setName('Denver');
        $entityManager->persist($personDenver);

        $personAlba = new Person();
        $personAlba->setName('Jessica Alba');
        $entityManager->persist($personAlba);

        // Création des castings
        $casting1 = new Casting();
        $casting1
            ->setMovie($movieFargo)
            ->setPerson($personMacy)
            ->setRole('THE méchant')
            ->setCreditOrder(2);
        $entityManager->persist($casting1);

        $casting2 = new Casting();
        $casting2
            ->setMovie($movieFargo)
            ->setPerson($personAlba)
            ->setRole('THE gentil')
            ->setCreditOrder(1);
        $entityManager->persist($casting2);

        $casting3 = new Casting();
        $casting3
            ->setMovie($movieFargo)
            ->setPerson($personDenver)
            ->setRole('THE detective')
            ->setCreditOrder(3);
        $entityManager->persist($casting3);

        $casting4 = new Casting();
        $casting4
            ->setMovie($movieGodzilla)
            ->setPerson($personDenver)
            ->setRole('Godzilla')
            ->setCreditOrder(1);
        $entityManager->persist($casting4);

        $casting5 = new Casting();
        $casting5
            ->setMovie($movieGodzilla)
            ->setPerson($personReno)
            ->setRole('THE detective')
            ->setCreditOrder(2);
        $entityManager->persist($casting5);

        $casting6 = new Casting();
        $casting6
            ->setMovie($movieGodzilla)
            ->setPerson($personLloyd)
            ->setRole('figuration')
            ->setCreditOrder(3);
        $entityManager->persist($casting6);

        $casting7 = new Casting();
        $casting7
            ->setMovie($movieBackToFuture)
            ->setPerson($personLloyd)
            ->setRole('DOC Emmett Brown')
            ->setCreditOrder(1);
        $entityManager->persist($casting7);

        $casting8 = new Casting();
        $casting8
            ->setMovie($movieBackToFuture)
            ->setPerson($personFox)
            ->setRole('Marty McFLY')
            ->setCreditOrder(2);
        $entityManager->persist($casting8);

        // Permet d'exécuter les requettes dans la BDD
        $entityManager->flush();
        
        return $this->render('sandbox/index.html.twig', [
            'controller_name' => 'SandboxController',
        ]);
    }
}
