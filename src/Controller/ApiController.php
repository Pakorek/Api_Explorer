<?php
namespace App\Controller;

use App\Entity\ApiActor;
use App\Entity\ApiCategory;
use App\Entity\ApiCreator;
use App\Entity\ApiEpisode;
use App\Entity\ApiProgram;
use App\Entity\ApiSeason;
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


}