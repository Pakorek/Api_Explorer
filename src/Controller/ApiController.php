<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/API", name="api_")
 *
 * Class ApiController
 * @package App\Controller
 */
class ApiController extends AbstractController
{
    const KEY = 'k_Gyn239Fh';


    /**
     * @Route("/", name="index")
     *
     * @return Response
     */
    public function index():Response
    {
        return $this->render('admin/index.html.twig');
    }


}