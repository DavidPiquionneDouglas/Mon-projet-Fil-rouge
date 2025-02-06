<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/user')]
final class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        // Rediriger l'utilisateur vers son propre profil
        return $this->redirectToRoute('app_user_show', ['id' => $this->getUser()->getId()]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $uphi): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hasher le mot de passe
            $user = $form->getData();
            $mdp = $user->getPassword();
            $mdp = $uphi->hashPassword($user, $mdp);
            $user->setPassword($mdp);

            // Récupérer la photo et définir le chemin
            $chemin = $this->getParameter('kernel.project_dir') . '/public/assets/image/user'; // Chemin absolu
            $fichier = $form['photo']->getData();
            if ($fichier) {
                $nouveauNomFichier = md5(uniqid()) . '.' . $fichier->guessExtension(); // Nom unique
                $fichier->move($chemin, $nouveauNomFichier);
                $user->setPhoto('/assets/image/user/' . $nouveauNomFichier); // Chemin relatif pour l'affichage
            }

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/newUser.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        // Vérifier si l'utilisateur connecté essaie d'accéder à son propre profil
        if ($this->getUser()->getId() !== $user->getId()) {
            throw new AccessDeniedException("Vous n'êtes pas autorisé à accéder à ce profil.");
        }

        return $this->render('user/showUser.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, UserPasswordHasherInterface $uphi): Response
    {
        // Vérifier si l'utilisateur connecté essaie de modifier son propre profil
        if ($this->getUser()->getId() !== $user->getId()) {
            throw new AccessDeniedException("Vous n'êtes pas autorisé à modifier ce profil.");
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hasher le mot de passe (seulement si le mot de passe a été modifié)
            if ($form->has('password') && $form->get('password')->getData()) {
                $mdp = $form->get('password')->getData();
                $mdp = $uphi->hashPassword($user, $mdp);
                $user->setPassword($mdp);
            }

            // Récupérer la photo et définir le chemin (seulement si une nouvelle photo a été téléchargée)
            if ($form->has('photo') && $form->get('photo')->getData()) {
                $chemin = $this->getParameter('kernel.project_dir') . '/public/assets/image/user';
                $fichier = $form['photo']->getData();
                $nouveauNomFichier = md5(uniqid()) . '.' . $fichier->guessExtension();
                $fichier->move($chemin, $nouveauNomFichier);
                $user->setPhoto('/assets/image/user/' . $nouveauNomFichier);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_user_show', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/editUser.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        // Vérifier si l'utilisateur connecté essaie de supprimer son propre profil
        if ($this->getUser()->getId() !== $user->getId()) {
            throw new AccessDeniedException("Vous n'êtes pas autorisé à supprimer ce profil.");
        }

        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
    }
}