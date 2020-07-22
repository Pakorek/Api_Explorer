<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 * @Route("/explorer", name="explorer_")
 *
 * Class UserController
 * @package App\Controller
 */
class UserController extends AbstractController
{
    /**
     * Home Page
     *
     * @Route("/", name="index")
     * @return Response
     */
    public function index(): Response
    {
//        $programs = $this->getDoctrine()
//            ->getRepository(Program::class)
//            ->findAll();
//
//        if (!$programs) {
//            throw $this->createNotFoundException('No program found in program\'s table');
//        }

        return $this->render('user/index.html.twig');
    }

    public function showAllApi(): Response
    {
        return $this->render('user/show.html.twig');
    }

    public function showCategories(): Response
    {
        return $this->render('admin/show_categories.html.twig');
    }

    public function showAPI(): Response
    {
        return $this->render('admin/api_page.html.twig');
    }

//    public function favoris(): Response
//    {
//        return $this->render('admin/parameters.html.twig');
//    }
}