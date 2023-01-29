<?php

namespace App\Controller\Admin;

use App\Entity\Ingredient;
use App\Entity\Pizza;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $session = $request->getSession();

        if (!$session->get('authenticated') || $session->get('role') !== 'admin') {
            return $this->redirectToRoute('pizza');
        }

        $routeBuilder = $this->container->get(AdminUrlGenerator::class);
        $url = $routeBuilder->setController(PizzaCrudController::class)->generateUrl();
        
        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Margherita');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Espace public', 'fa fa-home', 'pizza');
        yield MenuItem::linkToCrud('Ingredients', 'fas fa-palette', Ingredient::class);
        yield MenuItem::linkToCrud('Pizzas', 'fas fa-pizza-slice', Pizza::class);
    }
}
