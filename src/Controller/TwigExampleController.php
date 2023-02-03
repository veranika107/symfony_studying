<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TwigExampleController extends AbstractController
{

    private array $messages = [
        ['message' => 'Hello', 'created' => '2022/12/12'],
        ['message' => 'Hi', 'created' => '2022/10/12'],
        ['message' => 'Bye', 'created' => '2021/11/12']
    ];

    #[Route('/twig/{limit<\d+>?}', name: 'app_show_two')]
    public function showOne(int|NULL $limit)
    {
        return $this->render(
            'twig_example/show_one.html.twig',
            [
                'messages' => $this->messages,
                'limit' => $limit
            ]
        );
    }
}