<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Signin extends AbstractController
{
    #[Route('/', methods: ['POST'])]
    public function handle(Request $request, UserRepository $userRepository): Response
    {
        
        $user = $userRepository->findOneBy(['email' =>  $request->get('username')]);
        if (!password_verify($request->get('password'), $user->getPassword())) {
            dd('FALSE');
        }

        $session = $request->getSession();
        $session->set('authenticated', $user);

        return $this->redirectToRoute('pizza');
    }
}