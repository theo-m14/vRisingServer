<?php

namespace App\Controller;

use App\Entity\Review;
use App\Entity\Server;
use App\Repository\ReviewRepository;
use App\Repository\ServerRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use function PHPUnit\Framework\isEmpty;

class ReviewController extends AbstractController
{
    #[Route('serveur/{id}/creation-avis', name: 'app_review_create')]
    #[IsGranted('ROLE_USER')]
    public function create(Request $request, ReviewRepository $reviewManager, Server $server, ServerRepository $serverManager): Response
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

        //Update Server Note

        $note = $server->getNote();

        if($note){

            $serverReviews = $server->getReviews();
            $serverNote = 0;

            foreach($serverReviews as $oldReview){
                $serverNote+= $oldReview->getStar();
            }
            

            $serverNote = $serverNote / (count($serverReviews));

            $server->setNote($serverNote);

            $serverManager->add($server,true);
        }else{
            $note = $review->getStar();
            $server->setNote($note);
            $serverManager->add($server,true);
        }

        $this->addFlash('success', 'Merci pour votre avis !');
        return $this->redirectToRoute('app_server_readOne', ['id' => $server->getId()]);
    }

    #[Route('/serveur/{id}/suppression-avis', name:'app_review_delete')]
    #[IsGranted('ROLE_USER')]
    public function delete(Request $request, Server $server, ReviewRepository $reviewManager, ServerRepository $serverManager){

        $submittedToken = $request->request->get('token');

        if($this->isCsrfTokenValid('delete-review', $submittedToken)){

            $server->getReviews();

            foreach($server->getReviews() as $serverReview){
                if($serverReview->getUser() == $this->getUser() ){
                    $userReview = $serverReview;
                }
            }

            if(!$userReview){
                $this->addFlash('error',"Vous ne posséder pas d'avis sur ce serveur");
                $this->redirectToRoute('app_server_readOne', ['id' => $server->getId()]);
            }

            $reviewManager->remove($userReview, true);

            $serverReviews = $server->getReviews();
            if($serverReviews->isEmpty()){
                $server->setNote(null);
            }else{
                $serverNote = 0;

                foreach($serverReviews as $oldReview){
                        $serverNote+= $oldReview->getStar();
                }
                    
                $serverNote = $serverNote / (count($serverReviews));

                $server->setNote($serverNote);
            }

                $serverManager->add($server,true);
                return $this->redirectToRoute('app_server_readOne', ['id' => $server->getId()]);
        }

        $this->addFlash('error', "Demande non valide");
        return $this->redirectToRoute('app_server_readOne', ['id' => $server->getId()]);

    }
}
