<?php

namespace App\Controller;

use App\Entity\IMDB\Program;
use App\Form\BugReportType;
use App\Form\searchProgramType;
use App\Repository\APIRepository;
use App\Repository\IMDB\ProgramRepository;
use App\Services\apiManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/IMDB", name="api_IMDB_")
 */
class IMDBController extends APIController
{
    private $api;

    public function __construct(APIRepository $apiRepository)
    {
        $this->setApi($apiRepository->findOneBy(['name' => 'IMDB']));
    }

    /**
     * @Route("/", name="show", methods={"GET","POST"})
     * @param apiManager $apiManager
     * @param ProgramRepository $programRepo
     * @param Request $request
     * @return Response
     */
    public function show(apiManager $apiManager, ProgramRepository $programRepo, Request $request): Response
    {
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
            $response = $apiManager->getAPIId($search, $this->getApi()->getApiKey());

            return $this->render('api/IMDB/index.html.twig', [
                'api' => $this->getApi(),
                'programs' => $response,
                'programsExist' => $programsExist,
                'formSearch' => $formSearch->createView()
            ]);
        }

        // on program clic
        if (isset($_POST["get_details"])) {
            $apiId = $_POST["get_details"];
            $apiManager->updateIfNeed($apiId, $this->getApi()->getApiKey());
            $program = $programRepo->findOneBy(['API_id' => $apiId]);

            return $this->render('api/api_show_program.html.twig', [
                'api' => $this->getApi(),
                'program' => $program,
                'formSearch' => $formSearch->createView()
            ]);
        }

        // contact admin - bug report
        if ($bugReport->isSubmitted() && $bugReport->isValid()) {
            //TO DO:
            //send email to admin + thanks message to user
            //maybe thinking about 'pricing' : 10 requests / bug report

            return $this->render('api/IMDB/index.html.twig', [
                'api' => $this->getApi(),
                'formSearch' => $formSearch->createView()
            ]);
        }

        return $this->render('api/show.html.twig', [
            'api' => $this->getApi(),
            'formSearch' => $formSearch->createView(),
//            'bugReport' => $bugReport->createView()
        ]);
    }

    /**
     * @Route("/{api}", name="show_program", methods={"GET","POST"})
     * @param Program $program
     * @return Response
     */
    public function showProgram(Program $program)
    {
        return $this->render('api/IMDB/show.html.twig', ['api' => $this->getApi(), 'program' => $program]);
    }

    /**
     * @Route("/category/{categoryName<^[a-zA-Z-]+$>?null}", name="show_category")
     *
     * @param string $categoryName
     * @return Response
     */
    public function showByCategory(string $categoryName):Response
    {
        // API request to find by category
    }

    public function setApi($api): void
    {
        $this->api = $api;
    }

    public function getApi()
    {
        return $this->api;
    }


}
