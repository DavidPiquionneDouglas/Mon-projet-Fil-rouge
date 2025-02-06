<?php
namespace App\Controller;

use App\Entity\Taches;
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
    public function index(TachesRepository $tachesRepository): Response
    {
        $taches = $tachesRepository->findAll();
        
        $stats = [
            'en_cours' => count(array_filter($taches, fn($t) => $t->getStatut() === 'En cours')),
            'en_retard' => count(array_filter($taches, fn($t) => $t->getStatut() === 'En retard')),
            'terminee' => count(array_filter($taches, fn($t) => $t->getStatut() === 'Terminée')),
            'total' => count($taches)
        ];

        return $this->render('cpTache.html.twig', [
            'taches' => $taches,
            'stats' => $stats,
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
            'form' => $form->createView(),
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

    #[Route('/{id}/delete', name: 'app_tache_delete', methods: ['POST'])]
    public function delete(Request $request, Taches $tache, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $tache->getId(), $request->request->get('_token'))) {
            $entityManager->remove($tache);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_tache_index', [], Response::HTTP_SEE_OTHER);
    }
}
