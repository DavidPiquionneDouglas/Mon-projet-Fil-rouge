<?php
namespace App\Controller;

use App\Entity\Taches;
use App\Entity\User;
use App\Form\TacheType;
use App\Repository\TachesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/tache')]
class TacheController extends AbstractController
{
    #[Route('/', name: 'app_tache_index', methods: ['GET'])]
    public function index(TachesRepository $tachesRepository, EntityManagerInterface $entityManager): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $taches = $tachesRepository->findAll();
        } else {
            $taches = $tachesRepository->createQueryBuilder('t')
                ->where('t.assignedUser = :user')
                ->setParameter('user', $this->getUser())
                ->getQuery()
                ->getResult();
        }
        
        // Filtrer les tâches selon leur statut
        $taches_en_cours  = array_filter($taches, fn(Taches $t) => $t->getStatut() === 'En cours');
        $taches_en_retard = array_filter($taches, fn(Taches $t) => $t->getStatut() === 'En retard');
        $taches_termines = array_filter($taches, fn(Taches $t) => $t->getStatut() === 'Terminée');

        $stats = [
            'en_cours'  => count($taches_en_cours),
            'en_retard' => count($taches_en_retard),
            'terminee'  => count($taches_termines),
            'total'     => count($taches)
        ];

        // Récupérer tous les utilisateurs pour l'assignation
        $users = $entityManager->getRepository(User::class)->findAll();

        return $this->render('cpTache.html.twig', [
            'taches'             => $taches,
            'stats'              => $stats,
            'users'              => $users,
            'taches_en_cours'    => $taches_en_cours,
            'taches_en_retard'   => $taches_en_retard,
            'taches_termines'    => $taches_termines,
        ]);
    }
    
    #[Route('/new', name: 'app_tache_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tache = new Taches();
        $form = $this->createForm(TacheType::class, $tache);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tache);
            $entityManager->flush();

            return $this->redirectToRoute('app_tache_index');
        }

        return $this->render('newTache.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tache_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Taches $tache, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TacheType::class, $tache);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_tache_index');
        }

        return $this->render('showTache.html.twig', [
            'tache' => $tache,
            'form'  => $form->createView(),
        ]);
    }

    #[Route('/{id}/update-status', name: 'app_tache_update_status', methods: ['POST'])]
    public function updateStatus(Request $request, Taches $tache, EntityManagerInterface $entityManager): Response
    {
        $status = $request->request->get('statut');
        if (in_array($status, ['En cours', 'En retard', 'Terminée'])) {
            $tache->setStatut($status);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_tache_index');
    }

    // La méthode assign accepte désormais GET et POST
    #[Route('/{id}/assign', name: 'app_tache_assign', methods: ['GET', 'POST'])]
    public function assign(Request $request, Taches $tache, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            if ($this->isCsrfTokenValid('assign_tache' . $tache->getId(), $request->request->get('_token'))) {
                $assignedUserId = $request->request->get('assigned_user');

                if (empty($assignedUserId)) {
                    // Désassigner la tâche si aucune valeur n'est sélectionnée
                    $tache->setAssignedUser(null);
                } else {
                    $user = $entityManager->getRepository(User::class)->find($assignedUserId);
                    if ($user) {
                        $tache->setAssignedUser($user);
                    }
                }
                $entityManager->flush();
                $this->addFlash('success', 'Tâche assignée avec succès.');
                return $this->redirectToRoute('app_tache_index');
            }
        }

        // En GET, afficher le formulaire d'assignation
        $users = $entityManager->getRepository(User::class)->findAll();

        return $this->render('assignTache.html.twig', [
            'tache' => $tache,
            'users' => $users,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_tache_delete', methods: ['POST'])]
    public function delete(Request $request, Taches $tache, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $tache->getId(), $request->request->get('_token'))) {
            $entityManager->remove($tache);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_tache_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{id}/change-status', name: 'app_tache_change_status', methods: ['GET', 'POST'])]
    public function changeStatus(Request $request, Taches $tache, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            if ($this->isCsrfTokenValid('change_status' . $tache->getId(), $request->request->get('_token'))) {
                // Récupération du nouveau statut depuis le formulaire
                $status = $request->request->get('statut');
                // On vérifie que le statut est l'un des statuts attendus
                if (in_array($status, ['En cours', 'En retard', 'Terminée'])) {
                    $tache->setStatut($status);
                    $entityManager->flush();
                    $this->addFlash('success', 'Le statut de la tâche a été mis à jour.');
                } else {
                    $this->addFlash('error', 'Statut invalide.');
                }
                // Rediriger vers le tableau de bord (ou vers la page de détails)
                return $this->redirectToRoute('app_tache_index');
            }
        }
        
        // En GET, afficher le formulaire de modification du statut
        return $this->render('changeStatusTache.html.twig', [
            'tache' => $tache,
        ]);
    }
    
}
