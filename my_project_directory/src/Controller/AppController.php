<?php
// src/Controller/AppController.php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class AppController extends AbstractController
{
    /**
     * @Route("/HomePage",name="HomePage")
    */
    public function test(){
        return $this->render('Pages/homePage.html.twig',[]);
    }


 

}