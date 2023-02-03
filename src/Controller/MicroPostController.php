<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\MicroPost;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\MicroPostType;
use App\Repository\CommentRepository;
use App\Repository\MicroPostRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class MicroPostController extends AbstractController
{
    #[Route('/micro/post', name: 'app_micro_post')]
    public function index(MicroPostRepository $posts): Response
    {
        return $this->render('micro_post/index.html.twig', [
            'posts' => $posts->findAllWithComments(), // Will help to adjust execution time. findAll() is more heavy.
        ]);
    }

    #[Route('/micro/post/top-liked', name: 'app_micro_post_topliked')]
    public function topLiked(MicroPostRepository $posts): Response
    {
        return $this->render('micro_post/top_liked.html.twig', [
            'posts' => $posts->findAllWithMinimumLikes(1), // Will help to adjust execution time. findAll() is more heavy.
        ]);
    }

    #[Route('/micro/post/follows', name: 'app_micro_post_follows')]
    #[isGranted('IS_AUTHENTICATED_FULLY')]
    public function follows(MicroPostRepository $posts): Response
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        return $this->render('micro_post/follows.html.twig', [
            'posts' => $posts->findAllByAuthors($currentUser->getFollows()), // Will help to adjust execution time. findAll() is more heavy.
        ]);
    }

    #[Route('/micro/post/{post}', name: 'app_micro_post_show')]
    #[IsGranted(MicroPost::VIEW, 'post')]
    public function showOne(MicroPost $post): Response
    {
        return $this->render('micro_post/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/micro/post/add', name: 'app_micro_post_add', priority: 2)]
    #[IsGranted('ROLE_VERIFIED')]
    public function add(Request $request, MicroPostRepository $posts): Response {
//        $microPost = new MicroPost();
//        $form = $this->createFormBuilder($microPost)
//            ->add('title') // didn't specify any types, because symfony automatically took it from entity
//            ->add('text')
//            ->add('submit', SubmitType::class, ['label' => 'Save!'])
//            ->getForm();

        $form = $this->createForm(MicroPostType::class, new MicroPost());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $post = $form->getData();
            $post->setAuthor($this->getUser());
            $posts->save($post, true);

            $this->addFlash('success', 'Your micro post have been added.');
            return $this->redirectToRoute('app_micro_post');
        }

        return $this->renderForm(
            'micro_post/add.html.twig',
            [
                'form' => $form
            ]
        );
    }

    #[Route('/micro/post/{post}/edit', name: 'app_micro_post_edit', priority: 2)]
    #[IsGranted(MicroPost::EDIT, 'post')]
    public function edit(MicroPost $post, Request $request, MicroPostRepository $posts): Response
    {
//        $form = $this->createFormBuilder($post)
//            ->add('title') // didn't specify any types, because symfony automatically took it from entity
//            ->add('text')
//            ->add('submit', SubmitType::class, ['label' => 'Save!'])
//            ->getForm();
        $form = $this->createForm(MicroPostType::class, $post);

        $form->handleRequest($request);

        $this->denyAccessUnlessGranted(MicroPost::EDIT, $post); // If not all method should be blocked

        if ($form->isSubmitted() && $form->isValid())
        {
            $post = $form->getData();
            $posts->save($post, true);

            $this->addFlash('success', 'Your micro post have been updated.');
            return $this->redirectToRoute('app_micro_post');
        }

        return $this->renderForm(
            'micro_post/edit.html.twig',
            [
                'form' => $form,
                'post' => $post
            ]
        );
    }

    #[Route('/micro/post/{post}/comment', name: 'app_micro_post_comment', priority: 2)]
    public function addComment(MicroPost $post, Request $request, CommentRepository $comments): Response
    {
        $form = $this->createForm(CommentType::class, new Comment());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $comment = $form->getData();
            $comment->setPost($post);
            $comment->setAuthor($this->getUser());
            $comments->save($comment, true);

            $this->addFlash('success', 'Your comment have been updated.');
            return $this->redirectToRoute('app_micro_post_show', ['post' => $post->getId()]);
        }

        return $this->renderForm(
            'micro_post/comment.html.twig',
            [
                'form' => $form,
                'post' => $post,
            ]
        );
    }
}
