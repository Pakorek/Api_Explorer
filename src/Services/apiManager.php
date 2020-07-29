<?php

namespace App\Services;

use App\Entity\IMDB\Actor;
use App\Entity\IMDB\ApiActor;
use App\Entity\IMDB\ApiCategory;
use App\Entity\IMDB\ApiCreator;
use App\Entity\IMDB\ApiEpisode;
use App\Entity\IMDB\ApiProgram;
use App\Entity\IMDB\ApiSeason;
use App\Entity\IMDB\Category;
use App\Entity\IMDB\Creator;
use App\Entity\IMDB\Episode;
use App\Entity\IMDB\Program;
use App\Entity\IMDB\Season;

class apiManager extends abstractManager
{
    public function getAllApiRepo():array
    {
        return $repos = [
            'api_program' => $this->getDoctrine()->getRepository(ApiProgram::class)->findAll(),
            'api_season' => $this->getDoctrine()->getRepository(ApiSeason::class)->findAll(),
            'api_episode' => $this->getDoctrine()->getRepository(ApiEpisode::class)->findAll(),
            'api_actor' => $this->getDoctrine()->getRepository(ApiActor::class)->findAll(),
            'api_creator' => $this->getDoctrine()->getRepository(ApiCreator::class)->findAll(),
            'api_category' => $this->getDoctrine()->getRepository(ApiCategory::class)->findAll()
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

    public function fillApiDB($infos, $details)
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
        $this->getEm()->persist($program);

        foreach ($infos->actorList as $star) {
            $actor = new ApiActor();
            $actor->setApiId($star->id);
            $actor->setName($star->name);
            $actor->setAsCharacter($star->asCharacter);
            $actor->setImage($star->image);
            $this->getEm()->persist($actor);
        }

        foreach ($infos->tvSeriesInfo->creatorList as $creater) {
            $creator = new ApiCreator();
            $creator->setApiId($creater->id);
            $creator->setFullName($creater->name);
            $this->getEm()->persist($creator);
        }

        foreach ($infos->genreList as $genre) {
            $category = new ApiCategory();
            $category->setName($genre->value);
            $this->getEm()->persist($category);
        }

        for ($i=1;$i<=sizeof($infos->tvSeriesInfo->seasons);$i++) {
            $season = new ApiSeason();
            $season->setNumber($i);
            $season->setYear($details["season_$i"]->year);
            $season->setProgram($program);
            $this->getEm()->persist($season);

            foreach ($details["season_$i"]->episodes as $episod) {
                $episode = new ApiEpisode();
                $episode->setNumber($episod->episodeNumber);
                $episode->setTitle($episod->title);
                $episode->setPlot($episod->plot);
                $episode->setReleased($episod->released);
                $episode->setImage($episod->image);
                $episode->setSeason($season);
                $this->getEm()->persist($episode);
            }
        }
        $this->getEm()->flush();
    }

    public function updateBDD()
    {
        $repos = self::getAllApiRepo();

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
            $actorExist = $this->getDoctrine()
                ->getRepository(Actor::class)
                ->findOneBy(['name' => $_actor->getName()]);

            if (!$actorExist) {
                $actor = new Actor();
                $actor->setName($_actor->getName());
                $actor->setImage($_actor->getImage());
                $this->getEm()->persist($actor);
                $program->addActor($actor);
            }
        }

        foreach ($repos['api_creator'] as $_creator) {
            $creatorExist = $this->getDoctrine()
                ->getRepository(Creator::class)
                ->findOneBy(['fullName' => $_creator->getFullName()]);

            if (!$creatorExist) {
                $creator = new Creator();
                $creator->setFullName($_creator->getFullName());
                $this->getEm()->persist($creator);
                $program->addCreator($creator);
            }
        }

        foreach ($repos['api_category'] as $_cat) {
            $catExist = $this->getDoctrine()
                ->getRepository(Category::class)
                ->findOneBy(['name' => $_cat->getName()]);

            if (!$catExist) {
                $category = new Category();
                $category->setName($_cat->getName());
                $this->getEm()->persist($category);
                $program->addCategory($category);
            }
        }
        $this->getEm()->persist($program);

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
                    $this->getEm()->persist($episode);
                }
            }
            $this->getEm()->persist($season);
        }
        $this->getEm()->flush();

        // Clear API BDD
        $this->dropApiDB($repos);
    }

    public function dropApiDB(array $repos)
    {
        foreach ($repos as $repo => $obj) {
            if ($repo == 'api_program') {
                $this->getEm()->remove($repos['api_program'][0]);
            } else {
                foreach ($obj as $object) {
                    $this->getEm()->remove($object);
                }
            }
        }
        $this->getEm()->flush();
    }

    public function updateIfNeed($apiId, $key)
    {
        $isInDatabase = in_array($apiId, $this->getDoctrine()->getRepository(Program::class)->findAllApiKeys());
        if ($isInDatabase) {
            //check updated at with date_diff
            //update if > n days
        } else {
            // fill

            $infos = self::getProgramInfosWithAPIId($apiId, $key);
            $details = self::getAllDetails($apiId, sizeof($infos->tvSeriesInfo->seasons), $key);
            self::fillApiDB($infos, $details);
            self::updateBDD();
        }
    }
}