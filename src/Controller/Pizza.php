<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\PizzaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Pizza extends AbstractController
{
    #[Route('/pizza', name: 'pizza', methods: ['GET'])]
    public function handle(Request $request, UserRepository $userRepository, PizzaRepository $pizzaRepository): Response
    {
        $session = $request->getSession();

        if (!$session->get('authenticated')) {
            return $this->redirectToRoute('signin');
        }

        $user = $userRepository->find($session->get('authenticated'));
        $allergies = [];
        foreach ($user->getAllergies() as $allergy) {
            $allergies[] = $allergy->getId();
        }

        $pizzas = $pizzaRepository->findAll();
        foreach ($pizzas as $pizza) {
            $ingredientIds = [];
            foreach ($pizza->getIngredients() as $ingredient) {
                $ingredientIds[] = $ingredient->getId();
            }

            $pizza->isContainsAllergic = !empty(array_intersect($allergies, $ingredientIds));
        }

        return $this->render('pizzas.html.twig', [
            'path' => 'pizza',
            'fullName' => $session->get('fullName'),
            'pizzas' => $pizzaRepository->findAll(),
            'allergies' => $allergies,
            'role' => $session->get('role'),
        ]);
    }

    #[Route('/pizza/{id}', name: 'get_pizza', methods: ['GET'])]
    public function getPizza(int $id, Request $request, UserRepository $userRepository, PizzaRepository $pizzaRepository)
    {
        $session = $request->getSession();

        if (!$session->get('authenticated')) {
            return $this->redirectToRoute('signin');
        }

        $user = $userRepository->find($session->get('authenticated'));
        $allergies = [];
        foreach ($user->getAllergies() as $allergy) {
            $allergies[] = $allergy->getId();
        }

        return $this->render('pizza.html.twig', [
            'path' => '',
            'fullName' => $session->get('fullName'),
            'pizza' => $pizzaRepository->find($id),
            'allergies' => $allergies,
            'role' => $session->get('role'),
        ]);
    }

}