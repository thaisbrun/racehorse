<?php

namespace App\Controller;

use App\Entity\Chat;
use App\Form\ChatType;
use App\Repository\ChatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/chat')]
class ChatController extends AbstractController
{
    #[Route('/', name: 'app_chat_index', methods: ['GET'])]
    public function index(ChatRepository $chatRepository): Response
    {
        return $this->render('chat/index.html.twig', [
            'chats' => $chatRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_chat_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ChatRepository $chatRepository): Response
    {
        $chat = new Chat();
        $form = $this->createForm(ChatType::class, $chat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $chatRepository->add($chat, true);

            return $this->redirectToRoute('app_chat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('chat/new.html.twig', [
            'chat' => $chat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_chat_show', methods: ['GET'])]
    public function show(Chat $chat): Response
    {
        return $this->render('chat/show.html.twig', [
            'chat' => $chat,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_chat_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Chat $chat, ChatRepository $chatRepository): Response
    {
        $form = $this->createForm(ChatType::class, $chat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $chatRepository->add($chat, true);

            return $this->redirectToRoute('app_chat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('chat/edit.html.twig', [
            'chat' => $chat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_chat_delete', methods: ['POST'])]
    public function delete(Request $request, Chat $chat, ChatRepository $chatRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$chat->getId(), $request->request->get('_token'))) {
            $chatRepository->remove($chat, true);
        }

        return $this->redirectToRoute('app_chat_index', [], Response::HTTP_SEE_OTHER);
    }
}
