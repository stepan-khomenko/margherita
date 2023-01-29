<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Allergy extends AbstractController
{
    #[Route('/allergy', name: 'allergy', methods: ['GET'])]
    public function handle(Request $request, UserRepository $userRepository, IngredientRepository $ingredientRepository): Response
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

        if ($session->get('role') === 'admin') {
            return $this->redirect('/');
        }

        return $this->render('allergies.html.twig', [
            'path' => 'allergy',
            'fullName' => $session->get('fullName'),
            'ingredients' => $ingredientRepository->findAll(),
            'allergies' => $allergies,
            'role' => $session->get('role'),
        ]);
    }

    #[Route('/allergy', name: 'allergy_store', methods: ['POST'])]
    public function store(Request $request, UserRepository $userRepository, IngredientRepository $ingredientRepository, EntityManagerInterface $em): Response
    {
        $user = $userRepository->find($request->getSession()->get('authenticated'));
        $user->removeAllAllergies();

        $allergies = $request->get('allergies');

        if (!empty($allergies)) {
            foreach ($allergies as $allergy) {
                $user->addAllergy($ingredientRepository->find($allergy));
                $em->persist($user);
            }

            $em->flush();
        }

        return $this->redirect('/pizza');
    }
}