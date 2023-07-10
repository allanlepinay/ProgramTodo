<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('/home/task')]
class TaskController extends AbstractController
{
    #[Route('/new', name: 'app_task_new', methods: ['GET', 'POST'])]
    public function new(UserInterface $user, Request $request, TaskRepository $taskRepository, UserRepository $userRepository): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            // setup field which user doesn't have to fulfill
            $task->setCreationDate(new \DateTime());
            $task->setUser($user);
            $taskRepository->save($task, true);

            return $this->redirectToRoute('app_user_show', [
                'id' => $user->getId(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('task/new.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_task_edit', methods: ['GET', 'POST'])]
    public function edit(UserInterface $user, Request $request, Task $task, TaskRepository $taskRepository): Response
    {
        // if the user who try to modified the task isn't the same who create it, we show access denied
        // get task's user infos
        $taskUserId = $task->getUser()->getId();
        if($taskUserId !== $user->getId()){
            return $this->render('error/access_denied.html.twig');

        } else { // we can continue because it's the righ user
            $form = $this->createForm(TaskType::class, $task);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $taskRepository->save($task, true);

                return $this->redirectToRoute('app_user_show', [
                    'id' => $user->getId()
                ], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('task/edit.html.twig', [
                'task' => $task,
                'form' => $form,
            ]);
        }
    }

    #[Route('/{id}', name: 'app_task_delete', methods: ['POST'])]
    public function delete(UserInterface $user, Request $request, Task $task, TaskRepository $taskRepository): Response
    {
        // if the user who try to modified the task isn't the same who create it, we show access denied
        // get task's user infos
        $taskUserId = $task->getUser()->getId();
        if($taskUserId !== $user->getId()){
            return $this->render('error/access_denied.html.twig');
            
        } else { // we can continue because it's the righ user
            if ($this->isCsrfTokenValid('delete'.$task->getId(), $request->request->get('_token'))) {
                $taskRepository->remove($task, true);
            }
            // get the user for whom task was deleted
            $user = $task->getUser();

            return $this->redirectToRoute('app_user_show', [
                'id' => $user->getId()
            ], Response::HTTP_SEE_OTHER);
        }
    }
}
