<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AdminController extends AbstractController
{
    #[Route('/admin/assign-role/{id}', name: 'admin_assign_role')]
    #[IsGranted('ROLE_ADMIN')] 
    public function assignRole(User $user, EntityManagerInterface $em): Response
    {
        if (!$user) {
            return new Response('Utilisateur non trouvé.', 404);
        }

        $roles = array_unique(array_merge($user->getRoles(), ['ROLE_CHEF_PROJET']));
        $user->setRoles($roles);

        $em->persist($user);
        $em->flush();

        return new Response('Rôle ROLE_CHEF_PROJET attribué avec succès à ' . $user->getEmail());
    }

    #[Route('/chef-de-projet', name: 'chef_de_projet')]
    #[IsGranted('ROLE_CHEF_PROJET')] 
    public function index(): Response
    {
        return new Response('Bienvenue, Chef de Projet !');
    }

    #[Route('/admin/make-admin/{id}', name: 'admin_make_admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function makeAdmin(User $user, EntityManagerInterface $em): Response
    {
        $roles = array_unique(array_merge($user->getRoles(), ['ROLE_ADMIN']));
        $user->setRoles($roles);

        $em->persist($user);
        $em->flush();

        return new Response("L'utilisateur {$user->getId()} est maintenant ADMIN !");
    }
}
