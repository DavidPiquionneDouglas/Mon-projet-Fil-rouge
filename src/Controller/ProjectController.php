<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Form\AssignProjectType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/project')]
final class ProjectController extends AbstractController
{
    #[Route('/', name: 'app_project_index', methods: ['GET'])]
    public function index(ProjectRepository $projectRepository): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $projects = $projectRepository->findAll();
        } else {
            $projects = $projectRepository->createQueryBuilder('p')
                ->where('p.assignedUser = :user')
                ->setParameter('user', $this->getUser())
                ->getQuery()
                ->getResult();
        }
        
        $stats = [
            'en_cours' => $projectRepository->count(['statut' => 'En cours']),
            'en_retard' => $projectRepository->count(['statut' => 'En retard']),
            'terminee' => $projectRepository->count(['statut' => 'Terminée']),
            'total' => $projectRepository->count([]),
        ];
        
        return $this->render('project/indexProject.html.twig', [
            'projects' => $projects,
            'stats' => $stats,
        ]);
    }
    
    #[Route('/new', name: 'app_project_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($project);
            $entityManager->flush();

            return $this->redirectToRoute('app_project_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('project/formProject.html.twig', [
            'project' => $project,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_project_show', methods: ['GET'])]
    public function show(Project $project): Response
    {
        return $this->render('project/showProject.html.twig', [
            'project' => $project,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_project_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Project $project, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('chef_projet_dashboard', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('project/editProject.html.twig', [
            'project' => $project,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_project_delete', methods: ['POST'])]
    public function delete(Request $request, Project $project, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$project->getId(), $request->get('_token'))) {
            $entityManager->remove($project);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_project_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/assign', name: 'app_project_assign', methods: ['GET', 'POST'])]
    public function assign(Project $project, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(AssignProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Utilisateur assigné avec succès !');
            return $this->redirectToRoute('chef_projet_dashboard');
        }

        return $this->render('project/assign.html.twig', [
            'form' => $form->createView(),
            'project' => $project,
        ]);
    }
}