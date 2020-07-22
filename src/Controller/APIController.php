<?php

namespace App\Controller;

use App\Entity\API;
use App\Form\APIType;
use App\Repository\APIRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/a/p/i")
 */
class APIController extends AbstractController
{
    /**
     * @Route("/", name="a_p_i_index", methods={"GET"})
     */
    public function index(APIRepository $aPIRepository): Response
    {
        return $this->render('api/index.html.twig', [
            'a_p_is' => $aPIRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="a_p_i_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $aPI = new API();
        $form = $this->createForm(APIType::class, $aPI);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($aPI);
            $entityManager->flush();

            return $this->redirectToRoute('a_p_i_index');
        }

        return $this->render('api/new.html.twig', [
            'a_p_i' => $aPI,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="a_p_i_show", methods={"GET"})
     */
    public function show(API $aPI): Response
    {
        return $this->render('api/show.html.twig', [
            'a_p_i' => $aPI,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="a_p_i_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, API $aPI): Response
    {
        $form = $this->createForm(APIType::class, $aPI);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('a_p_i_index');
        }

        return $this->render('api/edit.html.twig', [
            'a_p_i' => $aPI,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="a_p_i_delete", methods={"DELETE"})
     */
    public function delete(Request $request, API $aPI): Response
    {
        if ($this->isCsrfTokenValid('delete'.$aPI->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($aPI);
            $entityManager->flush();
        }

        return $this->redirectToRoute('a_p_i_index');
    }
}
