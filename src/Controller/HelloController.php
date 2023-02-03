<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\MicroPost;
use App\Entity\User;
use App\Entity\UserProfile;
use App\Repository\CommentRepository;
use App\Repository\MicroPostRepository;
use App\Repository\UserProfileRepository;
use DateTime;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController
{
    private array $messages = ['Hello', 'world'];

    #[Route('/', name: 'app_index')]
    public function index(MicroPostRepository $posts, CommentRepository $comments): Response
    {
//        $user = new User(); // User will be saved without save() because of cascade.
//        $user->setEmail('email@email.example');
//        $user->setPassword('12345678');
//
//        $profile = new UserProfile();
//        $profile->setUser($user);
//        $profiles->save($profile, true);
//
//        $profile = $profiles->find(1);
//        $profiles->remove($profile); // Will delete User and UserProfile because of cascade (have a look at UserProfile.php user property).


         $post = new MicroPost();
         $post->setTitle('Hello');
         $post->setText('Hello');
         $post->setCreated(new DateTime());

         $comment = new Comment();
         $comment->setText('Hello');
         $comments->save($comment, true);

         $post->addComment($comment);
         $posts->save($post, true);

         $post = $posts->find(19); // By default it won't fetch all comments in it, to do it fetch 'eager should be set in MicroPost'.
         $post->getComments()->count();
         $comment = $post->getComments()[0];
         // $comment->setPost(null); // The only way to remove is call removeComment().

        return new Response(implode(',', array_slice($this->messages, 0, $limit)));
    }

    #[Route('/messages/{id<\d+>}', name: 'app_show_one')]
    public function showOne(int $id): Response
    {
        return new Response($this->messages[$id]);
    }
}