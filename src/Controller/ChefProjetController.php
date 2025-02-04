<?php

namespace App\Controller;

use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/chef-de-projet')]
class ChefProjetController extends AbstractController
{
    #[Route('/dashboard', name: 'chef_projet_dashboard')]
    #[IsGranted('ROLE_CHEF_PROJET')]
    public function dashboard(EntityManagerInterface $em): Response
    {
        $projects = $em->getRepository(Project::class)->findAll();
        $projectsEnCours = $em->getRepository(Project::class)->findBy(['statut' => 'en cours']);
        $projectsEnRetard = $em->getRepository(Project::class)->findBy(['statut' => 'en retard']);
        $projectsTermines = $em->getRepository(Project::class)->findBy(['statut' => 'terminé']);

                dump($projects);
        return $this->render('espaceChefProjet.html.twig', [
            'projects' => $projects,
            'projects_en_cours' => $projectsEnCours,
            'projects_en_retard' => $projectsEnRetard,
            'projects_termines' => $projectsTermines,
        ]);


        return $this->render('espaceChefProjet.html.twig', [
            'projects' => $projects,
            'projects_en_cours' => $projectsEnCours,
            'projects_en_retard' => $projectsEnRetard,
            'projects_termines' => $projectsTermines,
        ]);
    }
}
