<?php 

namespace App\Service;
use aharen\OMDbAPI;

class FilmDescriptionService
{
    private static $API_KEY = 'e2f59d';
    public function getDescription($nomFilm)
    {
        $omdb = new OMDbAPI(self::$API_KEY);
        $resultat = $omdb->fetch('t', $nomFilm);
        if($resultat->data->Response == 'False'){
            return "erreur lors de la recherche dans l'API";
        }
        else{
            return $resultat; 
        }
       
    }
}