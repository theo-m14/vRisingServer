<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;




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


    #[Route('/profile/edit-email', name: 'app_user_editEmail')]
    #[IsGranted('ROLE_USER')]
    public function editEmail(Request $request, UserRepository $userManager): Response
    {
        $user = $this->getUser();


        $form = $this->createFormBuilder($user)
            ->add('email', TextType::class, ['label' => 'Nouveau email'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            if ($userManager->checkIfEmailExist($form->get('email')->getData())) {
                $userManager->add($user, true);
                return $this->redirectToRoute('app_user_profil');
            }
            $this->addFlash('error', 'Cet email est déjà utilisé');
            return $this->redirectToRoute('app_user_editEmail');
        }

        return $this->render('user/changeInfo.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/profile/edit-username', name: 'app_user_editUsername')]
    #[IsGranted('ROLE_USER')]
    public function editUsername(Request $request, UserRepository $userManager): Response
    {
        $user = $this->getUser();


        $form = $this->createFormBuilder($user)
            ->add('username', TextType::class, ['label' => 'Nouveau pseudo'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            if ($userManager->checkIfEmailExist($form->get('username')->getData())) {
                $userManager->add($user, true);
                return $this->redirectToRoute('app_user_profil');
            }
            $this->addFlash('error', 'Ce pseudo est déjà utilisé');
            return $this->redirectToRoute('app_user_editUsername');
        }

        return $this->render('user/changeInfo.html.twig', ['form' => $form->createView()]);
    }
}
