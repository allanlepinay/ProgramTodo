<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('/home/user')]
class UserController extends AbstractController
{
    
    // #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, UserRepository $userRepository): Response
    // {
    //     $user = new User();
    //     $form = $this->createForm(UserType::class, $user);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $userRepository->save($user, true);

    //         return $this->redirectToRoute('app_user_show', [
    //             'id' => $user->getId(),
    //         ], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('user/new.html.twig', [
    //         'user' => $user,
    //         'form' => $form,
    //     ]);
    // }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(int $id, UserInterface $user): Response
    {
        // If the user who try to see the page isn't the right user, then show error
        if ($user->getId() !== $id) {
            return $this->render('error/access_denied.html.twig');

        } else { // if it's the right user, we continue
            return $this->render('user/show.html.twig', [
                'user' => $user,
            ]);
        }
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(int $id, Request $request, UserInterface $user, UserRepository $userRepository): Response
    {
        // If the user who try to see the page isn't the right user, then show error
        if ($user->getId() !== $id) {
            return $this->render('error/access_denied.html.twig');

        } else { // if it's the right user, we continue
            $form = $this->createForm(UserType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $userRepository->save($user, true);

                return $this->redirectToRoute('app_user_show', [
                    'id' => $user->getId()
                ], Response::HTTP_SEE_OTHER);        }

            return $this->renderForm('user/edit.html.twig', [
                'user' => $user,
                'form' => $form,
            ]);
        }
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        // If the user who try to see the page isn't the right user, then show error
        if ($user->getId() !== $id) {
            return $this->render('error/access_denied.html.twig');

        } else { // if it's the right user, we continue
            if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
                $userRepository->remove($user, true);
            }

            return $this->redirectToRoute('app_default', [], Response::HTTP_SEE_OTHER);
        }
    }
}
