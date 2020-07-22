<?php

namespace App\Controller;

use App\Entity\API;
use App\Entity\Program;
use App\Form\APIType;
use App\Repository\APIRepository;
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
     * @Route("/{id}", name="show", methods={"GET","POST"})
     * @param API $api
     * @param apiManager $apiManager
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function show(API $api, apiManager $apiManager, EntityManagerInterface $em): Response
    {
        $doctrine = $this->getDoctrine();

        if (isset($_POST['search_id']))
        {
            $search = $apiManager->cleanInput($_POST['search_id']);
            $response = $apiManager->getAPIId($search, $api->getApiKey());

            if (empty($response->results)) {
                $this->addFlash('info','Aucune série trouvée');
                return $this->redirectToRoute('api_show', ['id' => $api->getId()]);
            }

            if (count($response->results) === 1) {
                $this->addFlash('success','Une série trouvée !');
            } else {
                $this->addFlash('success',count($response->results) . ' séries trouvées');
            }

            return $this->render('api/show.html.twig', ['series' => $response, 'api' => $api]);
        }

        if (isset($_POST['search_by_id'])) {
            $id = $apiManager->cleanInput($_POST['search_by_id']);
            $infos = $apiManager->getProgramInfosWithAPIId($id, $api->getApiKey());
            $details = $apiManager->getAllDetails($id, sizeof($infos->tvSeriesInfo->seasons), $api->getApiKey());

            // MaJ BDD API - loops on seasons
            $apiManager->fillApiDB($em, $infos, $details);

            return $this->render('api/show.html.twig', ['infos' => $infos, 'details' => $details, 'api' => $api]);
        }

        if (isset($_POST['update_bdd'])) {
            $repos = $apiManager->getAllApiRepo($doctrine);
            $programExist = $doctrine
                ->getRepository(Program::class)
                ->findOneBy(['title' => $repos['api_program'][0]->getTitle()]);
            if (!$programExist) {
                $apiManager->updateBDD($em, $repos, $doctrine);
                $this->addFlash('success', 'Tous les détails du programme sont désormais dans la base de donnée');
            } else {
                $this->addFlash('success', 'Les données relatives au programme ont été mises à jour ');
            }
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
}
