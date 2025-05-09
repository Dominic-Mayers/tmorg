<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BusinessCardController
{
    #[Route( path: '/businesscard', name: 'app_businesscard' )]
    public function show(): Response
    {
        \ob_start(); 
        require("../governors/business_card.php"); 
        $resp = \ob_get_clean(); 
        return new Response($resp); 
        //return new Response("<html><body>Temporarily Out of Service.</body></html>"); 
    }

    #[Route(  path: '/pdfforbusinesscard', name: 'app_businesscardtopdf' )]
    public function topdf(): Response
    {
        \ob_start(); 
        require("../governors/business_card_to_pdf.php"); 
        $resp = \ob_get_clean(); 
        return new Response($resp); 
    }

    #[Route(  path: '/businesscard/test', name: 'app_businesscardtest' )]
    public function totest(): Response
    {
        return new Response("<html><body>Ceci est un test.</body></html>");
    }

}
