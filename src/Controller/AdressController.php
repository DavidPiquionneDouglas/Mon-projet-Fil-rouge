<?php

namespace App\Controller;

use App\Entity\Adress;
use App\Form\AdressType;
use App\Repository\AdressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/adress')]
final class AdressController extends AbstractController
{
    #[Route(name: 'app_adress_index', methods: ['GET'])]
    public function index(AdressRepository $adressRepository): Response
    {
        return $this->render('adresse/indexAdresse.html.twig', [
            'adresses' => $adressRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_adress_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $adress = new Adress();
        $form = $this->createForm(AdressType::class, $adress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $adress->setUser($this->getUser());
            $entityManager->persist($adress);
            $entityManager->flush();

            return $this->redirectToRoute('app_adress_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('adresse/newAdresse.html.twig', [
            'adress' => $adress,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_adress_show', methods: ['GET'])]
    public function show(Adress $adress): Response
    {
        return $this->render('adresse/showAdresse.html.twig', [
            'adress' => $adress,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_adress_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Adress $adress, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdressType::class, $adress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_adress_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('adresse/editAdresse.html.twig', [
            'adress' => $adress,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_adress_delete', methods: ['POST'])]
    public function delete(Request $request, Adress $adress, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$adress->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($adress);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_adress_index', [], Response::HTTP_SEE_OTHER);
    }
}
