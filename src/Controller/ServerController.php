<?php

namespace App\Controller;

use App\Entity\Server;
use App\Form\ServerType;
use App\Repository\ServerRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ServerController extends AbstractController
{
    #[Route('/', name: 'app_server_readAll')]
    public function readAll(ServerRepository $serverManager): Response
    {
        $servers = $serverManager->findAll();
        return $this->render('server/index.html.twig', [
            'servers' => $servers,
        ]);
    }

    #[Route('/creation-serveur', name: 'app_server_create')]
    public function create(Request $request, ServerRepository $serverManager): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }


        $newServer = new Server();

        $form = $this->createForm(ServerType::class, $newServer);

        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $newServer->setUserOwner($this->getUser());
            $serverManager->add($newServer, true);
            return $this->redirectToRoute('app_server_readOne', ['id' => $newServer->getId()]);
        }

        return $this->render('server/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/serveur/{id}', name: 'app_server_readOne')]
    public function readOne(Server $server): Response
    {
        return $this->render('server/readOne.html.twig', [
            'server' => $server,
        ]);
    }

    #[Route('/serveur-edition/{id}', name: 'app_server_edit')]
    #[IsGranted('ROLE_USER')]
    public function edit(Server $server, ServerRepository $serverManager, Request $request)
    {
        if ($server->getUserOwner() !== $this->getUser()) {
            $this->addFlash('error', 'Vous devez être propriétaire du serveur pour le modifier');
            return $this->redirectToRoute('app_server_readAll');
        }

        $form = $this->createForm(ServerType::class, $server);

        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $serverManager->add($server, true);
            return $this->redirectToRoute('app_server_readOne', ['id' => $server->getId()]);
        }

        return $this->render('server/form.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/serveur-suppression/{id}', name: 'app_server_delete')]
    #[IsGranted('ROLE_USER')]
    public function delete(Server $server, ServerRepository $serverManager, Request $request)
    {
        if ($server->getUserOwner() !== $this->getUser()) {
            $this->addFlash('error', 'Vous devez être propriétaire du serveur pour le modifier trest');
            return $this->redirectToRoute('app_server_readAll');
        }

        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('delete-server', $submittedToken)) {
            $serverManager->remove($server, true);
            return $this->redirectToRoute('app_user_server');
        }

        $this->addFlash('error', 'Vous devez être propriétaire du serveur pour le modifier caca');
        return $this->redirectToRoute('app_server_readAll');
    }
}
