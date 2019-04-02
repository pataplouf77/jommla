<?php
namespace App\Controller;

//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PropertyRepository;

class HomeController extends AbstractController
{
    /**
    * @Route("/", name="home")
    * @param PropertyRepository $repository
    * @return Response
    */
    public function index(PropertyRepository $repository): Response
        {
            $properties = $repository->findAll();
            //  $properties = $repository->findLatest();
            return $this->render('pages/home.html.twig', [
                'properties' => $properties  ]);
        }


}
