<?php

namespace App\Controller;

use App\Entity\MicroPost;
use App\Entity\User;
use App\Repository\MicroPostRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LikeController extends AbstractController
{
    #[Route('/like/{post}', name: 'app_like')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function like(
        MicroPost $post,
        MicroPostRepository $posts,
        Request $request
    ): Response
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        $post->addLikedBy($currentUser);
        $posts->save($post, true);

        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/unlike/{post}', name: 'app_unlike')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function unlike(
        MicroPost $post,
        MicroPostRepository $posts,
        Request $request
    ): Response
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        $post->removeLikedBy($currentUser);
        $posts->save($post, true);

        return $this->redirect($request->headers->get('referer'));
    }
}
