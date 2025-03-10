<?php
// src/Controller/BusinessCard.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BusinessCardController
{
    #[Route('/')]
    #[Route('/businesscard')]
    public function show(): Response
    {
        \ob_start(); 
        require("../governors/business_card.php"); 
        $resp = \ob_get_clean(); 
        return new Response($resp); 
        //return new Response("<html><body>Temporarily Out of Service.</body></html>"); 
    }

    #[Route('/businesscardtopdf')]
    public function topdf(): Response
    {
        \ob_start(); 
        require("../governors/business_card_to_pdf.php"); 
        $resp = \ob_get_clean(); 
        return new Response($resp); 
    }
}
