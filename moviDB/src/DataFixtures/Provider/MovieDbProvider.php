<?php

namespace App\DataFixtures\Provider;

class MovieDbProvider extends \Faker\Provider\Base 
{
    public function movieGenre()
    {
        $genres = [
            'Horreur',
            'Comédie', 
            'thriller', 
            'Science Fiction', 
            'Anime', 
            'Policier', 
            'Action', 
            'Aventure', 
            'Drame',
            'Slasher',
            'Porno',
        ];
        $genreToReturn = $genres[rand(0, count($genres) - 1)];
        return $genreToReturn;
    }
}