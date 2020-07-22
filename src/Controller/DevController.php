<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 * @Route("/dev", name="dev_")
 *
 * Class DevController
 * @package App\Controller
 */
class DevController extends AbstractController
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

        return $this->render('dev/index.html.twig');
    }
}