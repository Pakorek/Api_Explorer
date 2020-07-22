<?php

namespace App\Services;

use App\Entity\Actor;
use App\Entity\ApiActor;
use App\Entity\ApiCategory;
use App\Entity\ApiCreator;
use App\Entity\ApiEpisode;
use App\Entity\ApiProgram;
use App\Entity\ApiSeason;
use App\Entity\Category;
use App\Entity\Creator;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use Doctrine\ORM\EntityManagerInterface;

class apiManager
{
    public function cleanInput(string $input):string
    {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);

        return $input;
    }

    public function getAllApiRepo($doctrine):array
    {
        return $repos = [
            'api_program' => $doctrine->getRepository(ApiProgram::class)->findAll(),
            'api_season' => $doctrine->getRepository(ApiSeason::class)->findAll(),
            'api_episode' => $doctrine->getRepository(ApiEpisode::class)->findAll(),
            'api_actor' => $doctrine->getRepository(ApiActor::class)->findAll(),
            'api_creator' => $doctrine->getRepository(ApiCreator::class)->findAll(),
            'api_category' => $doctrine->getRepository(ApiCategory::class)->findAll()
        ];
    }

    public static function getAPIId(string $search, $key)
    {
        // appliquer une fonction à $search pour les cas avec plusieurs mots
        // ex: Breaking Bad         (un truc du genre replace(' ','%20',$search)



        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://imdb-api.com/en/API/SearchSeries/". $key . "/$search",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return json_decode($response);
    }


    public static function getProgramInfosWithAPIId(string $id, $key)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://imdb-api.com/en/API/Title/". $key ."/$id",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return json_decode($response);
    }

    public static function getAllDetails(string $id, int $seasons, $key):array
    {
        $details = [];
        $curl = curl_init();

        for ($i=1;$i<$seasons+1;$i++) {
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://imdb-api.com/en/API/SeasonEpisodes/". $key ."/$id/$i",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
            ));

            $response = curl_exec($curl);

            $details["season_$i"] = json_decode($response);
        }

        curl_close($curl);

        return $details;
    }



    public function fillApiDB(EntityManagerInterface $em, $infos, $details)
    {
        $program = new ApiProgram();
        $program->setTitle($infos->title);
        $program->setApiId($infos->id);
        $program->setYear(intval($infos->year));
        $program->setPlot($infos->plot);
        $program->setPoster($infos->image);
        $program->setRuntime(intval($infos->runtimeMins));
        $program->setAwards($infos->awards);
        $program->setNbSeasons(sizeof($infos->tvSeriesInfo->seasons));
        $program->setEndYear(intval($infos->tvSeriesInfo->yearEnd));
        $em->persist($program);

        foreach ($infos->actorList as $star) {
            $actor = new ApiActor();
            $actor->setApiId($star->id);
            $actor->setName($star->name);
            $actor->setAsCharacter($star->asCharacter);
            $actor->setImage($star->image);
            $em->persist($actor);
        }

        foreach ($infos->tvSeriesInfo->creatorList as $creater) {
            $creator = new ApiCreator();
            $creator->setApiId($creater->id);
            $creator->setFullName($creater->name);
            $em->persist($creator);
        }

        foreach ($infos->genreList as $genre) {
            $category = new ApiCategory();
            $category->setName($genre->value);
            $em->persist($category);
        }

        for ($i=1;$i<=sizeof($infos->tvSeriesInfo->seasons);$i++) {
            $season = new ApiSeason();
            $season->setNumber($i);
            $season->setYear($details["season_$i"]->year);
            $season->setProgram($program);
            $em->persist($season);

            foreach ($details["season_$i"]->episodes as $episod) {
                $episode = new ApiEpisode();
                $episode->setNumber($episod->episodeNumber);
                $episode->setTitle($episod->title);
                $episode->setPlot($episod->plot);
                $episode->setReleased($episod->released);
                $episode->setImage($episod->image);
                $episode->setSeason($season);
                $em->persist($episode);
            }
        }
        $em->flush();
    }

    public function updateBDD(EntityManagerInterface $em, array $repos, $doctrine)
    {
        $program = new Program();
        $program->setTitle($repos['api_program'][0]->getTitle());
        $program->setApiId($repos['api_program'][0]->getApiId());
        $program->setYear($repos['api_program'][0]->getYear());
        $program->setSummary($repos['api_program'][0]->getPlot());
        $program->setPoster($repos['api_program'][0]->getPoster());
        $program->setRuntime($repos['api_program'][0]->getRuntime());
        $program->setAwards($repos['api_program'][0]->getAwards());
        $program->setNbSeasons($repos['api_program'][0]->getNbSeasons());
        $program->setEndYear($repos['api_program'][0]->getEndYear());

        foreach ($repos['api_actor'] as $_actor) {
            $actorExist = $doctrine
                ->getRepository(Actor::class)
                ->findOneBy(['name' => $_actor->getName()]);

            if (!$actorExist) {
                $actor = new Actor();
                $actor->setName($_actor->getName());
                $actor->setImage($_actor->getImage());
                $em->persist($actor);
                $program->addActor($actor);
            }
        }

        foreach ($repos['api_creator'] as $_creator) {
            $creatorExist = $doctrine
                ->getRepository(Creator::class)
                ->findOneBy(['fullName' => $_creator->getFullName()]);

            if (!$creatorExist) {
                $creator = new Creator();
                $creator->setFullName($_creator->getFullName());
                $em->persist($creator);
                $program->addCreator($creator);
            }
        }

        foreach ($repos['api_category'] as $_cat) {
            $catExist = $doctrine
                ->getRepository(Category::class)
                ->findOneBy(['name' => $_cat->getName()]);

            if (!$catExist) {
                $category = new Category();
                $category->setName($_cat->getName());
                $em->persist($category);
                $program->addCategory($category);
            }
        }
        $em->persist($program);

        foreach ($repos['api_season'] as $ap_season) {
            $season = new Season();
            $season->setNumber($ap_season->getNumber());
            $season->setYear($ap_season->getYear());
            $season->setDescription('...');
            $season->setProgram($program);

            foreach ($repos['api_episode'] as $episod) {
                if ($episod->getSeason()->getNumber() == $ap_season->getNumber()) {
                    $episode = new Episode();
                    $episode->setNumber($episod->getNumber());
                    $episode->setTitle($episod->getTitle());
                    $episode->setSynopsis($episod->getPlot());
                    $episode->setPoster($episod->getImage());
                    $episode->setReleased($episod->getReleased());
                    $episode->setSeason($season);
                    $season->addEpisode($episode);
                    $em->persist($episode);
                }
            }
            $em->persist($season);
        }
        $em->flush();

        // Clear API BDD
        $this->dropApiDB($em, $repos);
    }

    public function dropApiDB(EntityManagerInterface $em, array $repos)
    {
        foreach ($repos as $repo => $obj) {
            if ($repo == 'api_program') {
                $em->remove($repos['api_program'][0]);
            } else {
                foreach ($obj as $object) {
                    $em->remove($object);
                }
            }
        }
        $em->flush();
    }
}