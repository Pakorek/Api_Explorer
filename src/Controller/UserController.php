<?php
namespace App\Controller;

use App\Entity\API;
use App\Entity\Category;
use App\Entity\Program;
use App\Repository\APIRepository;
use App\Repository\CategoryRepository;
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

    public function showCategories(APIRepository $apiRepo): Response
    {
        $categories = [];
        $apis = $apiRepo->findAll();

        foreach ($apis as $api) {
            $categories[] = $api->getCategory();
        }

        return $this->render('fragments/_categories.html.twig', [
            'categories' => $categories,
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

    public function showAPI(): Response
    {
        return $this->render('admin/api_page.html.twig');
    }

//    public function favoris(): Response
//    {
//        return $this->render('admin/parameters.html.twig');
//    }
}