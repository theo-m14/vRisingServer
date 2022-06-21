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
    #[IsGranted('ROLE_USER')]
    public function create(Request $request, ServerRepository $serverManager): Response
    {
        $newServer = new Server();

        $form = $this->createForm(ServerType::class, $newServer);

        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $newServer->setUserOwner($this->getUser());
            $serverManager->add($newServer, true);
            return $this->redirectToRoute('app_server_readAll');
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
}
