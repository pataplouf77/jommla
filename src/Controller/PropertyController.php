<?php
namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Property;
use App\Entity\PropertySearch;
use App\Form\ContactType;
use App\Form\PropertySearchType;
use App\Notification\ContactNotification;
use App\Repository\PropertyRepository;
use App\Repository\ContactRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController
{
      /**
       * @var PropertyRepository
       */
      private $repository;
      /**
       * @var ObjectManager
       */
      private $em;

      /**
       * PropertyController constructor.
       * @param PropertyRepository $repository
       * @param ObjectManager $em
       */
      public function __construct(PropertyRepository $repository, ObjectManager $em)
      {
          $this->repository = $repository;
          $this->em = $em;
      }
//
	/**
     * @var ContactRepository
     */
    private $repo;
    /**
     * @var ObjectManager
     */
    private $con;

    //

//
      /**
       * @Route("/biens", name="property.index")
       * @param PaginatorInterface $paginator
       * @param Request $request
       * @return Response
       */
       public function index(PaginatorInterface $paginator, Request $request): Response
     //public function index(Request $request): Response
     {
       $search = new PropertySearch();
       $form = $this->createForm(PropertySearchType::class, $search);
       $form->handleRequest($request);
       //$properties = $this->repository->findLatest($search);

      $properties = $paginator->paginate(
            $this->repository->findAllVisibleQuery($search),
            $request->query->getInt('page', 1),
            12
        );

       return $this->render('property/index.html.twig', [
                   'current_menu' => 'properties',
                   'properties'   => $properties,
                   'form'         => $form->createView()
               ]);

	     }
	//
	/**
       * @Route("/contacter", name="property.contacter")
       * @param Request $request
	   * @param ContactNotification $notification
       * @return Response
       */
       public function contacter(Request $request , ContactNotification $notification): Response
     //public function index(Request $request): Response
     {
       $contact = new Contact();
       $form = $this->createForm(ContactType::class, $contact);
       $form->handleRequest($request);
       if ($form->isSubmitted() && $form->isValid()) {
            $notification->notify($contact);
			$this->addFlash('success', 'Votre email a bien été envoyé');
			return $this->redirectToRoute('property.index');
	   }

       return $this->render('property/contact.html.twig', [
                   'current_menu' => 'contacts',
                   'form'         => $form->createView()
               ]);

	     }
	
	
	//
     
	 /**
          * @Route("/biens/{slug}-{id}", name="property.show", requirements={"slug": "[a-z0-9\-]*"})
          * @param Property $property
		
          * @param string $slug
          * @param Request $request
		  * @param ContactNotification $notification
          * @return Response
          */
         public function show(Property $property, string $slug, Request $request , ContactNotification $notification ): Response
         {
             if ($property->getSlug() !== $slug) {
                 return $this->redirectToRoute('property.show', [
                     'id'    => $property->getId(),
                     'slug'  => $property->getSlug()
                 ], 301);
             }
			 
			$contact = new Contact();
			$contact->setProperty($property);
			//$contact->setContact($contact);
			$form = $this->createForm(ContactType::class, $contact);
			$form->handleRequest($request);
			
			if ($form->isSubmitted() && $form->isValid()) {
            $notification->notify($contact);
			//$con = $this->getDoctrine()->getManager();
            //$con->persist($contact);
			//   @param Contact $contact
			
			//$this->con->persist($contact);
            //$con->flush();
            $this->addFlash('success', 'Votre email a bien été envoyé');

            return $this->redirectToRoute('property.show', [
                'id'    => $property->getId(),
                'slug'  => $property->getSlug()
            ]);
        }
			
           return $this->render('property/show.html.twig', [
            'property'      => $property,
            'current_menu'  => 'properties',
            'form'          => $form->createView()
			]);
         }

}
