<?php

namespace App\Controller;
use aharen\OMDbAPI;
use App\Entity\Films;
use App\Form\FilmsType;
use App\Repository\FilmsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/films')]
class FilmsController extends AbstractController
{
    #[Route('/', name: 'films_index', methods: ['GET'])]
    public function index(FilmsRepository $filmsRepository): Response
    {
        return $this->render('films/index.html.twig', [
            'films' => $filmsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'films_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $film = new Films();
        $form = $this->createForm(FilmsType::class, $film);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            /*
            $omdb = new OMDbAPI('e2f59d');
            $resultat = $omdb->fetch('t', $_POST["films"]["nomFilm"]);
            if($resultat->data->response == True){
            $desc = $resultat->data->Plot;
            $film->setDescription($desc);
            $entityManager->persist($film);
            $entityManager->flush();
        }
        else{
            echo "film non trouvé !";
        }
    
            return $this->redirectToRoute('films_index', [], Response::HTTP_SEE_OTHER);*/
        }

        return $this->renderForm('films/new.html.twig', [
            'film' => $film,
            'form' => $form,
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
        if ($this->isCsrfTokenValid('delete'.$film->getId(), $request->request->get('_token'))) {
            $entityManager->remove($film);
            $entityManager->flush();
        }

        return $this->redirectToRoute('films_index', [], Response::HTTP_SEE_OTHER);
    }
}
