<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Pizza extends AbstractController
{
    // TODO: add middleware
    #[Route('/pizza', name: 'pizza', methods: ['GET'])]
    public function handle(Request $request): Response
    {
        $user = $request->getSession()->get('authenticated');
        if (!$user) {
            dd('no access'); 
        }
    
        $fullName = $user->getFirstName(). ' ' .$user->getLastName();
 
        if ($user->getRole()->getName() === 'admin') {
            return $this->render('admin/pizza/list.html.twig', ['path' => 'pizza', 'fullName' => $fullName]);
        }

        return $this->render('client/pizza.html.twig', ['path' => 'pizza', 'fullName' => $fullName]);
    }
}