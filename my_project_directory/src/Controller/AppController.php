<?php
// src/Controller/AppController.php
namespace App\Controller;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Films;
use App\Form\FilmFormType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
class AppController extends AbstractController
{
    /**
     * @Route("/HomePage",name="HomePage")
    */
    public function Accueil(){

        return $this->render('Pages/homePage.html.twig',[]);
    }
    /**
     * @Route("/",name="HomePageRedirection")
    */
    public function redirectionAccueil(){
        
        return $this->redirectToRoute('Homepage');
    }

    /**
     * @Route("/AjoutFilm",name="AjoutFilm")
    */
    public function AjoutFilm(ManagerRegistry $doctrine){
        
        var_dump('11111');

        $film = new Films();


        $FilmManager = $doctrine->getManager();

        $form = $this->createForm(FilmFormType::class, $film);
        $request = new Request();
        $form->handleRequest($request);

        var_dump($form->isSubmitted());
        die;



        if ($form->isSubmitted()) {
            var_dump('dans le submit');
            die;
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            


            $data = $form->getData();
            $film->setNomFilm($data['nomFilm'])->setDescription($data['description'])->setNote($data['note'])->setNombrevotants($data['nombreVotants'])->
            $FilmManager->persist($film);
            $FilmManager->flush();

        }

        var_dump('fin');


            return $this->renderForm('Pages/AjoutFilm.html.twig', [
                'form' => $form,
                
            ]);




         // creates a task object and initializes some data for this example

    }


 

}