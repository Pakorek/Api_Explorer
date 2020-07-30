<?php

namespace App\Controller;

use App\Entity\BodyLocation;
use App\Entity\BodySublocation;
use App\Entity\Symptom;
use App\Repository\BodyLocationRepository;
use App\Repository\BodySublocationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends AbstractController
{
    const LANGUAGES = ['fr' => 'fr-fr', 'en' => 'en-gb', 'es' => 'es-es', 'de' => 'de-ch'];

    /**
     * @Route("/API", name="api_index")
     */
    public function index():Response
    {
        $bodyLocations = $this->getBodyLocation('fr');

        return $this->render('API/index.html.twig', ['bodyLocations' => $bodyLocations]);

    }


    public function getBodyLocation(string $language): array
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://priaid-symptom-checker-v1.p.rapidapi.com/body/locations?language=". self::LANGUAGES[$language],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "x-rapidapi-host: priaid-symptom-checker-v1.p.rapidapi.com",
                "x-rapidapi-key: 40a66d47a4mshff1a14197f18c3bp1f4d02jsne12e77f2fa5b"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        }
        return json_decode($response);
    }

    public function getBodySubLocation(int $id, string $language): array
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://priaid-symptom-checker-v1.p.rapidapi.com/body/locations/$id?language=". self::LANGUAGES[$language],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "x-rapidapi-host: priaid-symptom-checker-v1.p.rapidapi.com",
                "x-rapidapi-key: 40a66d47a4mshff1a14197f18c3bp1f4d02jsne12e77f2fa5b"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        }
        return json_decode($response);
    }

    public function getSymptomsByBodySubLocation($id): array
    {
        $gender = 'man';

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://priaid-symptom-checker-v1.p.rapidapi.com/symptoms/$id/$gender?language=fr-fr",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "x-rapidapi-host: priaid-symptom-checker-v1.p.rapidapi.com",
                "x-rapidapi-key: 40a66d47a4mshff1a14197f18c3bp1f4d02jsne12e77f2fa5b"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        }

        return json_decode($response);
    }

    /**
     * @Route("/API/update")
     *
     * @param EntityManagerInterface $em
     * @param BodySublocationRepository $bodys
     * @param BodyLocationRepository $bodyLocs
     * @return RedirectResponse
     */
    public function updateBodyLocation(EntityManagerInterface $em, BodySublocationRepository $bodys, BodyLocationRepository $bodyLocs)
    {
        $response = $this->getBodyLocation('fr');
        $response2 = $this->getBodySubLocation(6, 'fr');
        $response3 = $this->getSymptomsByBodySubLocation(23);

        foreach ($response as $location) {
            $bodyLocation = new BodyLocation();

            $bodyLocation->setAPIId($location->ID);
            $bodyLocation->setName($location->Name);
            $em->persist($bodyLocation);
        }

        foreach ($response2 as $location) {
            $subloc = new BodySublocation();

            $subloc->setAPIId($location->ID);
            $subloc->setName($location->Name);
            $subloc->setBodyLocation($bodyLocs->findOneBy(['API_Id'=> 6]));
            $em->persist($subloc);
        }



        foreach ($response3 as $location) {
            $symptom = new Symptom();

            $symptom->setAPIId($location->ID);
            $symptom->setName($location->Name);
            $symptom->setBodySublocation($bodys->findOneBy(['API_Id' => 23]));
            $em->persist($symptom);
        }
        $em->flush();

        return $this->redirectToRoute('api_index');
    }

    public static function getSpecialists(array $symptoms)
    {
        $symptom1 = $symptoms[0]->getId();
        $symptom2 = $symptoms[1]->getId();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://priaid-symptom-checker-v1.p.rapidapi.com/diagnosis/specialisations?symptoms=%5B$symptom1%2C$symptom2%5D&gender=male&year_of_birth=1984&language=fr-fr",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "x-rapidapi-host: priaid-symptom-checker-v1.p.rapidapi.com",
                "x-rapidapi-key: 13f6f37a97msh6d6910d586527f6p1f1906jsn0df75aac925e"
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        }

        return json_decode($response);
    }
}
