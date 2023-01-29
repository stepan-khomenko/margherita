<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Logout extends AbstractController
{
    #[Route('/logout', methods: ['GET'], name: 'logout')]
    public function handle(Request $request): Response
    {
        $request->getSession()->set('authenticated', null);

        return $this->redirect('/');
    }
}