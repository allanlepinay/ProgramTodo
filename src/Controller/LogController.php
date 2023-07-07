<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;


class LogController extends AbstractController
{
    #[Route('/log', name: 'app_log')]
    public function index(Request $request, #[Required] EntityManagerInterface $entityManager): Response
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Get form's data
            $username = $user->getName();
            $password = $user->getPassword();

            // We're looking for the user credentials
            $userRepository = $entityManager->getRepository(User::class);
            $findUser = $userRepository->findOneBy([
                'name' => $username,
                'password' => $password,
            ]);

            if ($findUser) {
                // Username/Password exist in database

                // We send the user to his page
                return $this->render('user/show.html.twig', [
                    'user' => $findUser,
                ]);        
            } else {
                // Username/password doesn't exist, return to signup page
                return $this->render('log/signup.html.twig',[
                    'username' => $username,
                    'password' => $password,
                ]);            
            }
        }
    
        // Dsiplay login form
        return $this->render('log/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/logout', name: 'logout')]
    public function logout(): Response
    {
        return $this->render('log/index.html.twig', [
            'controller_name' => 'LogController',
        ]);
    }
}
