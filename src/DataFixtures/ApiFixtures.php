<?php

namespace App\DataFixtures;

use App\Entity\API;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ApiFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $api = new API();
        $api->setName('IMDB');
        $api->setDescription('The IMDb-API is a web service for receiving movie, serial and cast information');
        $api->setCategory('Movie & Serial');
        $api->setApiKey('k_k6A30v26');
        $api->setUrl('https://imdb-api.com/');
        $manager->persist($api);
        $manager->flush();
    }
}
