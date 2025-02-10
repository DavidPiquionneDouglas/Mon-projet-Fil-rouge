<?php

namespace App\Controller\Admin;

use App\Entity\Project; 
use App\Entity\Taches;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

#[Route('/admin', name: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function __construct(private AdminUrlGenerator $adminUrlGenerator) {}

    #[Route('', name: 'admin_dashboard')]
    public function index(): Response
    {
        return $this->redirect($this->adminUrlGenerator->setController(TachesCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()->setTitle('QuickTask');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Tâches', 'fas fa-tasks', Taches::class);
        yield MenuItem::linkToCrud('Projets', 'fas fa-project-diagram', Project::class);

    }
}
