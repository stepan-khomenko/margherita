<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Index extends AbstractController
{
    #[Route('/', methods: ['GET'])]
    public function handle(Request $request, UserRepository $userRepository): Response
    {
        if ($request->getSession()->get('authenticated')) {
            $user = $userRepository->find($request->getSession()->get('authenticated'));

            if ($user->getRole()->getName() === 'admin') {
                return $this->redirect('/admin');
            }
    
            return $this->redirectToRoute('pizza');
        }

        return $this->render('signin.html.twig');
    }
}