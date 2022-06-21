<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;




class UserController extends AbstractController
{
    #[Route('/profil/serveur', name: 'app_user_server')]
    #[IsGranted('ROLE_USER')]
    public function server(): Response
    {
        $userServers = $this->getUser()->getServers();

        return $this->render('user/server.html.twig', ['servers' => $userServers]);
    }

    #[Route('/profil', name: 'app_user_profil')]
    #[IsGranted('ROLE_USER')]
    public function profil(): Response
    {
        return $this->render('user/profil.html.twig');
    }

    // #[Route('/profile/edit-email', name:'app_user_editEmail')]

}
