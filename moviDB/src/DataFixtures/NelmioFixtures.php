<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use \Nelmio\Alice\Loader\NativeLoader;

class NelmioFixtures extends Fixture
{
    public function load(ObjectManager $entityManager)
    {
        $loader = new NativeLoader();
       $entities = $loader->loadFile (__DIR__ . '/fixtures.yaml')->getObjects();

       foreach ($entities as $entity) 
       {
           $entityManager->persist($entity);
       };

       $entityManager->flush();

    }
}
