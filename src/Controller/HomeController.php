<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;

class HomeController
{
    #[Route( path: '/', name: 'app_home' )]
    public function show(): RedirectResponse
    {
       return new RedirectResponse('/login', 302);
    }

}
