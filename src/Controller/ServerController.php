<?php

namespace App\Controller;

use App\Entity\Server;
use App\Form\ServerType;
use App\Repository\ServerRepository;
use DateTime;
use DateTimeImmutable;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class ServerController extends AbstractController
{
    #[Route('/', name: 'app_server_readAll')]
    public function readAll(ServerRepository $serverManager, PaginatorInterface $paginator, Request $request): Response
    {
        // $serversData = $serverManager->findAll();

        // $serversData = $serverManager->findBy(array(), ['note' => 'desc']);
        $serversData = $serverManager->findAndOrderServer();

        $servers = $paginator->paginate(
            $serversData,
            $request->query->getInt('page', 1),
            8,
        );

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
            $newServer->setCreatedAt(DateTimeImmutable::createFromMutable(new DateTime()));
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
            $this->addFlash('error', 'Vous devez être propriétaire du serveur pour le modifier');
            return $this->redirectToRoute('app_server_readAll');
        }

        $submittedToken = $request->request->get('token');

        if ($this->isCsrfTokenValid('delete-server', $submittedToken)) {
            $serverManager->remove($server, true);
            return $this->redirectToRoute('app_user_server');
        }

        $this->addFlash('error', 'Vous devez être propriétaire du serveur pour le modifier');
        return $this->redirectToRoute('app_server_readAll');
    }

    #[Route('/server-search', name: 'app_server_search')]
    public function search(ServerRepository $serverRepository, Request $request, PaginatorInterface $paginator)
    {
        $name = $request->query->get('name');

        $type = $request->query->get('type');
        //verif des champs
        if ($type !== "pve" and $type !== 'pvp' and $type !== "all") {
            return new JsonResponse([], $status = 400);
        }

        $openDate = $request->query->get('openDate');

        if ($openDate !== "past" and $openDate !== 'incomming' and $openDate !== 'all') {
            return new JsonResponse([], $status = 400);
        }

        $clan_size = $request->query->get('clan_size');

        if ($clan_size !== 'all' and $clan_size !== '1' and $clan_size !== '2' and $clan_size !== '3' and $clan_size !== '4') {
            return new JsonResponse([], $status = 400);
        }

        $wipe = $request->query->get('wipe');

        if ($wipe !== 'all' and $wipe !== 'with' and $wipe !== 'without') {
            return new JsonResponse([], $status = 400);
        }

        $discord = $request->query->get('discord');

        if ($discord !== 'all' and $discord !== 'with' and $discord !== 'without') {
            return new JsonResponse([], $status = 400);
        }

        $searchedServer = $serverRepository->searchServer($name, $type, $openDate, $clan_size, $discord, $wipe);

        $serverPaginate =  $paginator->paginate(
            $searchedServer,
            $request->query->getInt('page', 1),
            8,
        );

        return new JsonResponse(['content' => $this->renderView('server/serverData.html.twig', ['servers' => $serverPaginate])]);
    }
}
