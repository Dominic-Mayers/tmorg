<?php
// src/Controller/BusinessCard.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BusinessCard
{
    #[Route('/')]
    #[Route('/businesscard')]
    public function show(): Response
    {
        \ob_start(); 
        require("../governors/business_card.php"); 
        $resp = \ob_get_clean(); 
        return new Response($resp); 
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
