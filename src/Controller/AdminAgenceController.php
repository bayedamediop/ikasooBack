<?php

namespace App\Controller;
use App\Entity\Articles;
use App\Entity\Client;
use App\Entity\Reservations;
use App\Repository\ArticlesRepository;
use App\Repository\ReservationsRepository;
use App\Repository\UserRepository;
use App\Service\adminAgence;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;

class AdminAgenceController extends AbstractController
{
    private $manager;

    /**
     * @Route("/admin/agence", name="admin_agence")
     */
    public function index(): Response
    {
        return $this->render('admin_agence/index.html.twig', [
            'controller_name' => 'AdminAgenceController',
        ]);
    }
// ______________ publier un article  ______________________
    /**
     * @Route (
     *     name="publication",
     *      path="/api/adminhotel/article",
     *      methods={"POST"},
     *     defaults={
     *           "__controller"="App\Controller\AdminAgenceController::publication",
     *           "__api_ressource_class"=Articles::class,
     *           "__api_collection_operation_name"="publication_d_un_article"
     *         }
     * )
     */
    public function publication(SerializerInterface $serializer, Request $request,UserRepository $repository)
    {

        $userConnecte = $this->getUser()->getId();
        // dd($userConnecte);
        $ucecreer = $repository->find((int)$userConnecte);
        //dd($ucecreer);
        $article = $request->request->all() ;
        //dd($article);
        $image_article = $request->files->get("image_article");
        //specify entity
        //dd($photo);
        if(!$image_article)
        {
            return new JsonResponse("veuillez mettre une images d article",Response::HTTP_BAD_REQUEST,[],true);
        }

        $picture3_d = $request->files->get("picture3_d");
        //specify entity
        //dd($photo);
        if(!$picture3_d)
        {
            return new JsonResponse("veuillez mettre une images de 3 D",Response::HTTP_BAD_REQUEST,[],true);
        }
        //$base64 = base64_decode($imagedata);
        $photoBlob = fopen($image_article->getRealPath(),"rb");
        $photoBlob3d = fopen($picture3_d->getRealPath(),"rb");

        //$articles = $serializer->denormalize($article, "App\Entity\Article");

        $articles = new  Articles();
        $articles->setTitre($article['titre']);
        $articles->setDescription($article['description']);
        $articles->setImageArticle($photoBlob);
        $articles->setImage3D($photoBlob3d);
        $articles ->setPrix($article['prix']);
        $articles ->setAdresse($article['adresse_article']);
        $articles->setCreateAt(new \DateTime());
        $articles->setUser($ucecreer);
        // dd($articles);
        $em = $this->getDoctrine()->getManager();
        $em->persist($articles);
        $em->flush();

        return $this->json("success",201);

    }

    // ______________ reservation d' un client dans un hotel  ______________________
    /**
     * @Route (
     *     name="reservation",
     *      path="/api/article/reserver",
     *      methods={"POST"},
     *     defaults={
     *           "__controller"="App\Controller\AdminAgenceController::reservation",
     *           "__api_ressource_class"=Articles::class,
     *           "__api_collection_operation_name"="reservation_d_un_client"
     *         }
     * )
     */
    public function reservation( Request $request,ReservationsRepository $repository,ArticlesRepository $articleRepository): JsonResponse
    {
        $json = json_decode($request->getContent(), 'json');
        //dd($json['reservation'][0]['article']);
        $article=$articleRepository->find((int)$json['reservation'][0]['article']);
        // dd($article);
        $dateFin=new \DateTime($json['reservation'][0]['dateFin']);
        // dd($dateFin);
        $dateDebut=new \DateTime($json['reservation'][0]['dateDebut']);
        $reservation = $repository->findOneBy(['article' => $article], ['id' => 'desc']);


        $client = new Client();
        $client->setNomComplet($json['prenomClient'])

            ->setTelephoneClient($json['telephoneClient'])
            ->setEmailClient($json['emailClient']);
        $em = $this->getDoctrine()->getManager();
        $em->persist($client);
        $reservation = new Reservations();
        $reservation->setDateDebut($dateDebut)
            ->setDateFin(($dateFin) )
            ->setClient($client)
            ->setArticle($article);
        $em->persist($reservation);
        $em->flush();
        return new JsonResponse([
            'status' => 200,
            'message' => ('Votre reservation a ete bien enregistrer!!! merci '
            )
        ], 200);


//            $client = new Client();
//            $client->setPrenomClient($json['client']['prenomClient'])
//                ->setNomClient($json['client']['nomClient'])
//                ->setTelephoneClient($json['client']['telephoneClient'])
//                ->setEmailClient($json['client']['emailClient']);
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($client);
//            $reservation = new Rerservation();
//            $reservation->setDateReservation($dateDebut)
//                ->setDateFin(($dateFin) )
//                ->setClient($client)
//                ->setArticle($article);
//            $em->persist($reservation);
//            $em->flush();
//            return new JsonResponse([
//                'status' => 200,
//                'message' => ('Votre reservation a ete bien enregistrée!!!! merci '
//                )
//            ], 200);
    }
    // ___________________ modiffication d'un article ______________________

    /**
     *
     *   * @Route (
     *     name="putArticleId",
     *      path="/api/admin/article/{id}",
     *      methods={"PUT"},
     *     defaults={
     *           "__controller"="App\Controller\AdminAgenceController::putArticleId",
     *           "__api_ressource_class"=Articles::class,
     *           "__api_collection_operation_name"="put_ArticleId"
     *         }
     * )
     */
    public function putArticleId($id, UserService $service, Request $request,
                                 EntityManagerInterface $manager, SerializerInterface $serializer, ArticlesRepository $u)
    {

        $article = $service->getAttributes($request);
       // $userUpdate = $this->manager->getRepository(User::class)->find($id);
        $articleForm= $service->getAttributes($request, 'image3D');
        // dd($userForm);
        //$userUpdate = $service->PutUser($request, 'avatar');
        // dd($userUpdate);
        $articleForm = $manager->getRepository(Articles::class)->find($id);
        foreach($article as $key=>$valeur){
            $setter = 'set'.ucfirst(strtolower($key));
            if(method_exists(Articles::class, $setter)){

                $articleForm->$setter($valeur);
                }

        }
        // dd($user);
        $manager->flush();
        return new JsonResponse("success",200,[],true);
    }

    // _______________________________archiver un user-------------------------

    /**
     * @Route(
     *  name = "archiveArticle",
     *  path = "/api/article/{id}",
     *  methods = {"PUT"},
     *  defaults  = {
     *      "__controller"="App\Controller\AdminAgenceController::archiveArticle",
     *      "__api_ressource_class"=Articles::class,
     *      "__api_collection_operation_name"="archive_article"
     * }
     * )
     */
    public function archiveArticle($id,ArticlesRepository $articleRepository,EntityManagerInterface $manager)
    {
        $user = $articleRepository->find($id);
       $user->setArchivage(new DateTimeZone);
        $manager->flush();
        return new JsonResponse("Article Archivé!!!!!!!",200,[],true);

    }
    // _______________________________archiver un article-------------------------

    /**
     * @Route(
     *  name = "getReservationHotel",
     *  path = "/api/admin/article/{id}",
     *  methods = {"DELETE"},
     *  defaults  = {
     *      "__controller"="App\Controller\AdminAgenceController::getReservationHotel",
     *      "__api_ressource_class"=Articles::class,
     *      "__api_collection_operation_name"="get_Reservation_Hotel"
     * }
     * )
     */
    public function getReservationHotel($id,ArticlesRepository $articleRepository, TokenStorageInterface $token,EntityManagerInterface $manager)
    {

        $userConnecte = $token->getToken();
        // dd($userConnecte);
        //$userConnecte = $token->getToken()->getUser()->getId();
        $user = $articleRepository->find($id);
        $user->setArchivage(true);
        $manager->flush();
        return new JsonResponse("Article Archivé!!!!!!!",200,[],true);

    }

    // ___________________ valider une reservation_____________________________
    /**
     * @Route(
     *  name = "validerReservation",
     *  path = "/api/admin/validerReservation/{id}",
     *  methods = {"PUT"},
     *  defaults  = {
     *      "__controller"="App\Controller\AdminAgenceController::validerReservation",
     *      "__api_ressource_class"=Reservations::class,
     *      "__api_collection_operation_name"="valider_reservation"
     * }
     * )
     */
    public function validerReservation($id,ReservationsRepository $rerservationRepository,EntityManagerInterface $manager)
    {
        $reservation = $rerservationRepository->find($id);
        //dd($reservation);
        $reservation->setDateValidation(new \DateTime());
        //$manager->remove($reservation);
        $manager->flush();
        return new JsonResponse("La validation effectue avec success !!!!!!!",200,[],true);
    }

// ___________________annuler une reservation_____________________________
    /**
     * @Route(
     *  name = "annulerReservation",
     *  path = "/api/admin/annulerReservation/{id}",
     *  methods = {"PUT"},
     *  defaults  = {
     *      "__controller"="App\Controller\AdminAgenceController::annulerReservation",
     *      "__api_ressource_class"=Reservations::class,
     *      "__api_collection_operation_name"="annuler_reservation"
     * }
     * )
     */
    public function annulerReservation($id,ReservationsRepository $rerservationRepository,EntityManagerInterface $manager)
    {
        $reservation = $rerservationRepository->find($id);
        //dd($reservation);
        $reservation->setDateAnnulation(new \DateTime());
        //$manager->remove($reservation);
        $manager->flush();
        return new JsonResponse("L' annulation effectue avec success !!!!!!!",200,[],true);
    }


}
