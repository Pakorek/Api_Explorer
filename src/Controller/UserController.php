<?php
namespace App\Controller;

use App\Entity\API;
use App\Entity\User;
use App\Form\searchApiType;
use App\Form\UserType;
use App\Repository\APIRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     * @param Request $request
     * @param APIRepository $apiRepo
     * @return Response
     */
    public function index(Request $request, APIRepository $apiRepo): Response
    {
        $form = $this->createForm(searchApiType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $keyword = $form->get('search')->getData();
            $apis = $apiRepo->findByKeyword($keyword);

            return $this->render('api/index.html.twig', ['apis' => $apis]);
        }

        return $this->render('user/index.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Embedding Controller in fragments/_nav_bar.html.twig
     *
     * @param APIRepository $apiRepo
     * @return Response
     */
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
        $apis = $this->getDoctrine()
            ->getRepository(API::class)
            ->findBy(['category' => $categoryName], ['name' => 'ASC']);

        return $this->render('user/show_by_category.html.twig', [
            'apis' => $apis,
        ]);
    }

    /**
     * @Route("/profil/{user}", name="profil")
     * @param User $user
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function profil(User $user, Request $request)
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
//            $user->getImageFile()->move('uploads', 'test');
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success','Votre profil a bien été mis à jour');

            return $this->redirectToRoute('explorer_profil', ['user' => $user->getId()]);
        }

        return $this->render('user/profil.html.twig', [
            'form' => $form->createView(),
        ]);

    }

//    public function favoris(): Response
//    {
//        return $this->render('admin/parameters.html.twig');
//    }
}