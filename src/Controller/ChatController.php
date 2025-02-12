<?php
// src/Controller/ChatController.php
namespace App\Controller;

use App\Entity\ChatMessage;
use App\Form\ChatMessageType;
use App\Repository\ChatMessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/chat')]
class ChatController extends AbstractController
{
    #[Route('/', name: 'app_chat', methods: ['GET', 'POST'])]
    public function index(Request $request, ChatMessageRepository $chatMessageRepository, EntityManagerInterface $entityManager): Response
    {
        $message = new ChatMessage();
        $form = $this->createForm(ChatMessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setUser($this->getUser());
            $message->setCreatedAt(new \DateTime());
            $entityManager->persist($message);
            $entityManager->flush();

            return $this->redirectToRoute('app_chat');
        }

        $messages = $chatMessageRepository->findBy([], ['createdAt' => 'ASC']);

        return $this->render('chat/index.html.twig', [
            'form' => $form->createView(),
            'messages' => $messages,
        ]);
    }
}
