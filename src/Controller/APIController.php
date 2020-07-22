<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Entity\API;
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
use App\Form\APIType;
use App\Repository\ApiActorRepository;
use App\Repository\ApiCategoryRepository;
use App\Repository\ApiCreatorRepository;
use App\Repository\ApiEpisodeRepository;
use App\Repository\ApiProgramRepository;
use App\Repository\APIRepository;
use App\Repository\ApiSeasonRepository;
use App\Services\apiManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="api_")
 */
class APIController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param APIRepository $apiRepository
     * @return Response
     */
    public function index(APIRepository $apiRepository): Response
    {
        return $this->render('api/index.html.twig', [
            'apis' => $apiRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $api = new API();
        $form = $this->createForm(APIType::class, $api);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($api);
            $entityManager->flush();

            return $this->redirectToRoute('api_index');
        }

        return $this->render('api/new.html.twig', [
            'api' => $api,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET","POST"})
     * @param API $api
     * @param apiManager $apiManager
     * @return Response
     */
    public function show(API $api, apiManager $apiManager, EntityManagerInterface $em): Response
    {
        if (isset($_POST['search_id']))
        {
            $search = $apiManager->cleanInput($_POST['search_id']);
            $response = self::getAPIId($search, $api->getApiKey());

            return $this->render('api/show.html.twig', ['series' => $response, 'api' => $api]);
        }

        if (isset($_POST['search_by_id'])) {
            $id = $apiManager->cleanInput($_POST['search_by_id']);
            $infos = self::getProgramInformationsWithAPIId($id, $api->getApiKey());
            $details = self::getAllDetails($id, sizeof($infos->tvSeriesInfo->seasons), $api->getApiKey());

            // MaJ BDD API - loops on seasons
            $apiManager->getAllDetails($em, $infos, $details);

            return $this->render('api/show.html.twig', ['infos' => $infos, 'details' => $details, 'api' => $api]);
        }


        return $this->render('api/show.html.twig', ['api' => $api]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param API $api
     * @return Response
     */
    public function edit(Request $request, API $api): Response
    {
        $form = $this->createForm(APIType::class, $api);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('api_index');
        }

        return $this->render('api/edit.html.twig', [
            'api' => $api,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param API $api
     * @return Response
     */
    public function delete(Request $request, API $api): Response
    {
        if ($this->isCsrfTokenValid('delete'.$api->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($api);
            $entityManager->flush();
        }

        return $this->redirectToRoute('api_index');
    }

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


    /**
     * @Route("/dropDB", name="drop")
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     */
    public function dropDB(EntityManagerInterface $em):RedirectResponse
    {
        $repos = $this->getAllApiRepo();

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

        return $this->redirectToRoute('admin_getSerie');
    }

    /**
     * get API id from IMDB API
     * and pick up the official title format
     *
     * @param string $search
     * @param $key
     * @return mixed
     */
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

    /**
     * get details from one program with API_id
     *
     * @param string $id
     * @param $key
     * @return mixed
     */
    public static function getProgramInformationsWithAPIId(string $id, $key)
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

    /**
     * get details from each season
     *
     * @param string $id
     * @param int $seasons
     * @param $key
     * @return array
     */
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
}
