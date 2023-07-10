<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Entity\Task;
use App\Form\TaskType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;


class LogController extends AbstractController
{
    #[Route('/log', name: 'app_log')]
    public function index(): Response
    {
        // Dsiplay login form
        return $this->render('log/index.html.twig', []);
    }

    #[Route('/logout', name: 'logout')]
    public function logout(): Response
    {
        return $this->render('log/index.html.twig', [
            'controller_name' => 'LogController',
        ]);
    }

    #[Route('/login', name: 'login')]
    public function login(Request $request, EntityManagerInterface $entityManager): Response
    {
        // get form's data
        $username = $request->request->get('name');
        $password = $request->request->get('password');
        
        // We're looking for the user credentials
        $userRepository = $entityManager->getRepository(User::class);
        $findUser = $userRepository->findOneBy([
            'name' => $username,
            'password' => $password,
        ]);

        if ($findUser) {
            // Username/Password exist in database
    
            // We send the user to his page     
            return $this->redirectToRoute('app_user_show', [
                'id' => $findUser->getId(),
            ]);
        } else {
            // Username/password doesn't exist, return to signup page
            return $this->redirectToRoute('app_user_new', []);        
        }
    }

}
