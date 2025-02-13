<?php

namespace App\Controller;

use App\Entity\User;  
use App\Form\InscriptionType; 
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class InscriptionController extends AbstractController
{
    /**
     * @Route("/inscription", name="inscription")
     */
    public function inscription(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        
        $form = $this->createForm(InscriptionType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $user->getPassword();
            $encodedPassword = $passwordHasher->hashPassword($user, $form->get('password')->getData());
            $user->setPassword($encodedPassword);

            $photo = $form->get('photo')->getData();
            if ($photo) {
                $uploadsDir = $this->getParameter('kernel.project_dir') . '/public/assets/image/user';
                $fileName = md5(uniqid()) . '.' . $photo->guessExtension();

                try {
                    $photo->move($uploadsDir, $fileName);
                    $user->setPhoto('assets/image/user/' . $fileName);
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Erreur lors du téléchargement de la photo.');
                }
            }


            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Votre compte a été créé avec succès. Vous pouvez vous connecter.');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('newUser.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
