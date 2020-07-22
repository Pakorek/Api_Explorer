<?php

namespace App\Services;

use App\Entity\ApiActor;
use App\Entity\ApiCategory;
use App\Entity\ApiCreator;
use App\Entity\ApiEpisode;
use App\Entity\ApiProgram;
use App\Entity\ApiSeason;
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

    public function getAllDetails(EntityManagerInterface $em, $infos, $details)
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
}