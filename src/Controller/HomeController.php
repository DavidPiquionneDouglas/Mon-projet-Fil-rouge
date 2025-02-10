<?php

namespace App\Controller;

use App\Classes\Mail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $email=new Mail();
            $content = "welcome.html";
            $user = $this->getUser();

            if($user){
            $vars = [
                'prenom' => $user->getFirstname() . ' ' . $user->getLastname(),
                'service' => $user->getService()
            ];
        }else{
            $vars = NULL;
        }
            $email->send("quicktask@yopmail.com", "Sung", "Bienvenue", $content, $vars);
        
        return $this->render('home/home.html.twig', [
            'nom' => 'QuickTask',
        ]);
    }

}
