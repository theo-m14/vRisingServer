<?php

namespace App\Controller;

use App\Entity\Review;
use App\Entity\Server;
use App\Repository\ReviewRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReviewController extends AbstractController
{
    #[Route('serveur/{id}/creation-avis', name: 'app_review_create')]
    #[IsGranted('ROLE_USER')]
    public function create(Request $request, ReviewRepository $reviewManager, Server $server): Response
    {
        if (!$request->request->get('rating')) {
            $this->addFlash('error', 'Veuillez sélectionner une note pour poster un avis');
            return $this->redirectToRoute('app_server_readOne', ['id' => $server->getId()]);
        }

        if ($request->request->get('rating') < 0 || $request->request->get('rating') > 5) {
            $this->addFlash('error', 'La note doit être comprise entre 0 et 5');
            return $this->redirectToRoute('app_server_readOne', ['id' => $server->getId()]);
        }

        if (strlen($request->request->get('review-text')) != 0 and strlen($request->request->get('review-text')) < 4) {
            $this->addFlash('error', 'Si vous souhaitez poster un commentaire lié à votre avis merci de rentrer au moins 4 caractères, sinon laissez vide');
            return $this->redirectToRoute('app_server_readOne', ['id' => $server->getId()]);
        }

        $review = new Review();
        $review->setStar($request->request->get('rating'))->setText($request->request->get('review-text'))->setServer($server)->setUser($this->getUser());

        $reviewManager->add($review, true);

        $this->addFlash('success', 'Merci pour votre avis !');
        return $this->redirectToRoute('app_server_readOne', ['id' => $server->getId()]);
    }
}
