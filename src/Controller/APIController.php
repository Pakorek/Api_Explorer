<?php

namespace App\Controller;

use App\Entity\API;
use App\Entity\IMDB\Program;
use App\Form\APIType;
use App\Form\BugReportType;
use App\Form\searchProgramType;
use App\Repository\APIRepository;
use App\Repository\ProgramRepository;
use App\Services\apiManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @Route("/category/{categoryName<^[a-zA-Z-]+$>?null}", name="show_category")
     *
     * @param string $categoryName
     * @return Response
     */
    public function showByCategory(string $categoryName):Response
    {
        if (!$categoryName) {
            throw $this
                ->createNotFoundException('No category has been sent to find programs');
        }

        // En partant du principe que toutes les catégories sont au format Capitalize (BDD)
        // Et pour pallier l'éventuelle erreur type 'science_fiction' au lieu de 'science-fiction'
        $categoryName = preg_replace(
            '/_/',
            '-', ucwords(trim(strip_tags($categoryName)), "_")
        );

        /*
                // On récupère l'Objet $category
                // On pourra ainsi récupérer le category_id correspondant au categoryName
                $category = $this->getDoctrine()
                    ->getRepository(Category::class)
                    ->findOneBy(['name' => $categoryName]);

                // for now, one api / category, but eventually ManyToMany later
                $apis = $this->getDoctrine()
                    ->getRepository(Program::class)
                    ->findBy(['category' => $category->getId()], ['name' => 'ASC']);
        */
        // for now, one api / category, but eventually ManyToMany later
        $apis = $this->getDoctrine()
            ->getRepository(API::class)
            ->findBy(['category' => $categoryName], ['name' => 'ASC']);

        return $this->render('user/show_by_category.html.twig', [
            'apis' => $apis,
        ]);
    }


    /**
     * @Route("/{api}", name="show", methods={"GET","POST"})
     * @param API $api
     * @param apiManager $apiManager
     * @param ProgramRepository $programRepo
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return Response
     */
    public function show(API $api, apiManager $apiManager, ProgramRepository $programRepo, EntityManagerInterface $em, Request $request): Response
    {
        $doctrine = $this->getDoctrine();

        ////////////////////////////////////////////////////////////////////////////// USER SEARCH ////////////////////
        $formSearch = $this->createForm(searchProgramType::class);
        $formSearch->handleRequest($request);

        $bugReport = $this->createForm(BugReportType::class);
        $bugReport->handleRequest($request);

        //get API id, title and image with keyword
        if ($formSearch->isSubmitted() && $formSearch->isValid()) {
            $keyword = $formSearch->get('searchSerie')->getData();
            $programsExist = $programRepo->findByKeyword($keyword);
            $search = $apiManager->cleanInput($keyword);
            $response = $apiManager->getAPIId($search, $api->getApiKey());

            return $this->render('api/IMDB/index.html.twig', [
                'api' => $api,
                'programs' => $response,
                'programsExist' => $programsExist,
                'formSearch' => $formSearch->createView()
                ]);
        }

        //contact admin - bug report
        if ($bugReport->isSubmitted() && $bugReport->isValid()) {

            return $this->render('api/IMDB/index.html.twig', [
                'api' => $api,
                'formSearch' => $formSearch->createView()
                ]);
        }

        if (isset($_POST["get_details"])) {
            $apiId = $_POST["get_details"];
            $apiManager->updateIfNeed($apiId, $api->getApiKey());
            $program = $programRepo->findOneBy(['API_id' => $apiId]);

            return $this->render('api/api_show_program.html.twig', [
                'api' => $api,
                'program' => $program,
                'formSearch' => $formSearch->createView()
            ]);
        }

        ////////////////////////////////////////////////////////////////////////////// ADMIN SEARCH ////////////////////
        if (isset($_POST['search_id']))
        {
            $search = $apiManager->cleanInput($_POST['search_id']);
            //get API id and title
            $response = $apiManager->getAPIId($search, $api->getApiKey());
            //check if some program already exist in DB
            //create query builder in Program Repository
            $programs = $programRepo->findAll();
            $apisId = [];
            foreach ($programs as $program) {
                $apisId[] = $program->getAPIId();
            }

            if (empty($response->results)) {
                $this->addFlash('info','Aucune série trouvée');
                return $this->redirectToRoute('api_show', ['id' => $api->getId()]);
            }

            if (count($response->results) === 1) {
                $this->addFlash('success','Une série trouvée !');
            } else {
                $this->addFlash('success',count($response->results) . ' séries trouvées');
            }

            return $this->render('api/show.html.twig', ['series' => $response, 'api' => $api, 'apisId' => $apisId]);
        }

        if (isset($_POST['search_by_id'])) {
            $id = $apiManager->cleanInput($_POST['search_by_id']);
            $infos = $apiManager->getProgramInfosWithAPIId($id, $api->getApiKey());
            $details = $apiManager->getAllDetails($id, sizeof($infos->tvSeriesInfo->seasons), $api->getApiKey());

            // MaJ BDD API - loops on seasons
            $apiManager->fillApiDB($infos, $details);

            return $this->render('api/show.html.twig', ['infos' => $infos, 'details' => $details, 'api' => $api]);
        }

        if (isset($_POST['update_bdd'])) {
            $repos = $apiManager->getAllApiRepo();
            $programExist = $doctrine
                ->getRepository(Program::class)
                ->findOneBy(['title' => $repos['api_program'][0]->getTitle()]);
            if (!$programExist) {
                $apiManager->updateBDD();
                $this->addFlash('success', 'Tous les détails du programme sont désormais dans la base de donnée');
            } else {
                $this->addFlash('success', 'Les données relatives au programme ont été mises à jour ');
            }
        }

        return $this->render('api/show.html.twig', [
            'api' => $api,
            'formSearch' => $formSearch->createView(),
//            'bugReport' => $bugReport->createView()
        ]);
    }

    /**
     * @Route("/{api}", name="show_program", methods={"GET","POST"})
     * @param API $api
     * @param Program $program
     * @return Response
     */
    public function showProgram(API $api, Program $program)
    {
        return $this->render('api/IMDB/show.html.twig', ['api' => $api, 'program' => $program]);
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
}
