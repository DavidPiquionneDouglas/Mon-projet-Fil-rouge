<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\InscriptionType;
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
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
    
        return $this->redirectToRoute('app_user_show', ['id' => $this->getUser()->getId()]);
    }
    
    #[Route('/register', name: 'app_user_register', methods: ['GET', 'POST'])]
    public function register(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }
    
        $user = new User();
        $form = $this->createForm(InscriptionType::class, $user);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Vérification des mots de passe
            $password = $form->get('password')->getData();
            $confirmPassword = $form->get('confirmPassword')->getData();
    
            if ($password !== $confirmPassword) {
                $this->addFlash('error', 'Les mots de passe ne correspondent pas.');
                return $this->redirectToRoute('app_user_register');
            }
    
            // Hashage du mot de passe
            $user->setPassword($passwordHasher->hashPassword($user, $password));
    
            // Upload de la photo
            $photo = $form->get('photo')->getData();
            if ($photo) {
                $uploadsDir = $this->getParameter('kernel.project_dir') . '/public/assets/image/user';
                if (!is_dir($uploadsDir)) {
                    mkdir($uploadsDir, 0777, true);
                }
    
                $fileName = md5(uniqid()) . '.' . $photo->guessExtension();
                try {
                    $photo->move($uploadsDir, $fileName);
                    $user->setPhoto('assets/image/user/' . $fileName);
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Erreur lors du téléchargement de la photo.');
                }
            }
    
            // Enregistrement en base de données
            $entityManager->persist($user);
            $entityManager->flush();
    
            $this->addFlash('success', 'Votre compte a été créé avec succès ! Connectez-vous.');
            return $this->redirectToRoute('app_login');
        }
    
        return $this->render('user/inscription.html.twig', [
            'form' => $form->createView(),
        ]);
    }                
    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
    
        if ($this->getUser()->getId() !== $user->getId()) {
            throw new AccessDeniedException("Vous n'êtes pas autorisé à accéder à ce profil.");
        }
    
        return $this->render('user/showUser.html.twig', [
            'user' => $user,
        ]);
    }
    
    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        User $user,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
    
        if ($this->getUser()->getId() !== $user->getId()) {
            throw new AccessDeniedException("Vous n'êtes pas autorisé à modifier ce profil.");
        }
    
        $form = $this->createForm(InscriptionType::class, $user);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$this->isCsrfTokenValid('edit_user' . $user->getId(), $request->request->get('_token'))) {
                throw new AccessDeniedException("Token CSRF invalide.");
            }
    
            if (!empty($form->get('password')->getData())) {
                $user->setPassword($passwordHasher->hashPassword($user, $form->get('password')->getData()));
            }
    
            $photo = $form->get('photo')->getData();
            if ($photo) {
                $uploadsDir = $this->getParameter('kernel.project_dir') . '/public/assets/image/user';
                $fileName = md5(uniqid()) . '.' . $photo->getClientOriginalExtension();
                try {
                    $photo->move($uploadsDir, $fileName);
                    $user->setPhoto('/assets/image/user/' . $fileName);
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Erreur lors du téléchargement de la photo.');
                }
            }
    
            $user->setFirstName($user->getFirstName() ?: '');
            $user->setMatricule($user->getMatricule() ?: '');
            $user->setBirthday($user->getBirthday() ?: null);
    
            $entityManager->flush();
            $this->addFlash('success', 'Votre profil a bien été mis à jour !');
            return $this->redirectToRoute('app_user_show', ['id' => $user->getId()]);
        }
    
        return $this->render('user/editUser.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/{id}/delete', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
    
        if ($this->getUser()->getId() !== $user->getId()) {
            throw new AccessDeniedException("Vous n'êtes pas autorisé à supprimer ce profil.");
        }
    
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }
    
        return $this->redirectToRoute('app_home');
    }
}
