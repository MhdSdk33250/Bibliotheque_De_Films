<?php

namespace App\Controller;

use App\Entity\Films;
use App\Form\FilmsType;
use App\Form\PasswordType;
use App\Repository\FilmsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FilmDescriptionService;






#[Route('/')]
class FilmsController extends AbstractController
{
    #[Route('/', name: 'films_index', methods: ['GET'])]
    public function index(FilmsRepository $filmsRepository): Response
    {   $erreur = "";
        
        if(isset($_GET[0]['erreur'])){
            $erreur = $_GET[0]['erreur'];
        }
        return $this->render('films/index.html.twig', [
            'films' => $filmsRepository->findBy([],['note'=>'ASC','nomFilm' => 'DESC']),'erreur'=>$erreur
        ]);
    }

    #[Route('/new', name: 'films_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $erreur = "";
        if(isset($_GET['erreur'])){
            $erreur = $_GET['erreur'];
        }
        $film = new Films();
        $form = $this->createForm(FilmsType::class, $film);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $filmDescriptionService = new filmDescriptionService();

            $resultat = $filmDescriptionService->getDescription($_POST["films"]["nomFilm"]);

            
            if($resultat == "erreur lors de la recherche dans l'API"){
                return $this->redirectToRoute('films_new', ['erreur'=>$resultat], Response::HTTP_SEE_OTHER);
               
            }else{
                $desc = $resultat->data->Plot;
                $film->setDescription($desc);
                $entityManager->persist($film);
                $entityManager->flush();
                return $this->redirectToRoute('films_index', [], Response::HTTP_SEE_OTHER);
            }
            
        
        
            
        
    
            
        }

        return $this->renderForm('films/new.html.twig', [
            'film' => $film,
            'form' => $form,
            'erreur'=>$erreur,
        ]);
    }

    #[Route('/{id}', name: 'films_show', methods: ['GET'])]
    public function show(Films $film): Response
    {
        return $this->render('films/show.html.twig', [
            'film' => $film,
        ]);
    }

    #[Route('/{id}/edit', name: 'films_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Films $film, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FilmsType::class, $film);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('films_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('films/edit.html.twig', [
            'film' => $film,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'films_delete', methods: ['POST'])]
    public function delete(Request $request, Films $film, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PasswordType::class, $film);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            
            $mdp = "leprofdephpavanceeestunbgetilvamemettreun20sur20";
            $mdpPosted = $_POST['password']['mot_de_passe'];
            

            if($mdp == $mdpPosted){
                $id = $request->attributes->get('id');
                $film = $entityManager->getRepository(Films::class)->find($id);
                $entityManager->remove($film);
                $entityManager->flush();
                return $this->redirectToRoute('films_index', [[]], Response::HTTP_SEE_OTHER);
            }
            else{
                return $this->redirectToRoute('films_index', [['erreur'=>"mot de passe admin incorrect"]], Response::HTTP_SEE_OTHER);
            }
            
        }

        return $this->renderForm('films/password.html.twig', [
            'film' => $film,
            'form' => $form,
        ]);
        
            
            
            return $this->redirectToRoute('films_index', [], Response::HTTP_SEE_OTHER);
           
        

        return $this->redirectToRoute('films_index', [], Response::HTTP_SEE_OTHER);
    }

}
