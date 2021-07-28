<?php

namespace App\DataFixtures;

use App\DataFixtures\Provider\MovieDbProvider;
use App\Entity\Casting;
use App\Entity\Genre;
use App\Entity\Movie;
use App\Entity\Person;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $entityManager)
    {
        // On instancie un objet faker qui va nous permettre de générer du texte aléatoirement
        $faker = \Faker\Factory::create('fr_FR');

        // On veut fournir un fournisseur de 
        $faker->addProvider(new MovieDbProvider($faker));
        // Création de genres
        // On va garder une trace des entités générées pour pouvoir les réutiliser dans les relations
        $genres = [];
        for ($i = 0 ; $i < 10 ; $i++)
        {
            $genre= new Genre();
            $genre->setName($faker->unique()->movieGenre());
            $entityManager->persist($genre);

            // On veut ce souvenir de tous les genres
            $genres[] = $genre;
        }

        //création de movie
        $movies = [];
        for ($i = 0 ; $i < 50 ; $i++)
        {
            $movie = new Movie();
            $movie->setTitle($faker->realText(50));

            // on définit par film un nombre de genre à ajouter
            $nbGenreToAdd = 8;
            $keysToAdd = array_rand($genres, $nbGenreToAdd);
            // array_rand renvoi soit une clef soit un tableau
            // Dans le cas ou on reçoit une clef, on en fait un tableau pour que la boucle fonctionne
            if (! is_array($keysToAdd))
            {
                $keysToAdd = [$keysToAdd];
            }
            foreach ($keysToAdd as $key) 
            {
                $movie->addGenre($genres[$key]);
            }
            $entityManager->persist($movie);
            $movies[] = $movie;
        }

        // Création des personnes
        $persons = [];
        for ($i = 0 ; $i < 500 ; $i++) 
        {            
            $person = new Person();
            $person->setName($faker->unique()->name());
            $entityManager->persist($person);

            $persons[] = $person;
        }

        // Création des castings
        for ($i = 0 ; $i < 500 ; $i++) 
        {
            $casting = new Casting();

            // On récupère une personne et un movie depuis nos listes
            $castingMovie = $movies[rand(0, count($movies) - 1)];
            $castingPerson = $persons[rand(0, count($persons) - 1)];
            $casting
            ->setMovie($castingMovie)
            ->setPerson($castingPerson)
            ->setRole($faker->firstName() . ' le ' . $faker->jobTitle())
            ->setCreditOrder(rand(0, $i));
            $entityManager->persist($casting);
        }

        $entityManager->flush();
    }
}
// composer require fakerphp/faker