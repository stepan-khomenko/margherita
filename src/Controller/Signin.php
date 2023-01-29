<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Signin extends AbstractController
{
    #[Route('/', methods: ['POST'], name: 'signin')]
    public function handle(Request $request, UserRepository $userRepository): Response
    {
        
        $user = $userRepository->findOneBy(['email' =>  $request->get('username')]);
        if (!password_verify($request->get('password'), $user->getPassword())) {
            return $this->redirectToRoute('signin');
        }

        $fullName = $user->getFirstName(). ' ' .$user->getLastName();

        $session = $request->getSession();
        $session->set('authenticated', $user->getId());
        $session->set('fullName', $fullName);
        $session->set('role', $user->getRole()->getName());

        if ($user->getRole()->getName() === 'admin') {
            return $this->redirect('/admin');
        }

        return $this->redirectToRoute('pizza');
    }
}