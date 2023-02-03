<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FollowerController extends AbstractController
{
    #[Route('/follow/{user}', name: 'app_follow')]
    public function follow(
        User $user,
        ManagerRegistry $doctrine,
        Request $request)
    : Response
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        if ($user->getId() !== $currentUser->getId()) {
            $currentUser->follow($user);
            $doctrine->getManager()->flush();
        }

        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/unfollow/{user}', name: 'app_unfollow')]
    public function unfollow(
        User $user,
        ManagerRegistry $doctrine,
        Request $request)
    : Response
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        $currentUser->unfollow($user);
        $doctrine->getManager()->flush();

        return $this->redirect($request->headers->get('referer'));
    }
}
