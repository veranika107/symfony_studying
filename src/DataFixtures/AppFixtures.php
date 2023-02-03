<?php

namespace App\DataFixtures;

use App\Entity\MicroPost;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user1 = new User();
        $user1->setEmail('test@rest.com');
        $user1->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $user1,
                    '12345678'
                )
            );
        $manager->persist($user1);

        $user2 = new User();
        $user2->setEmail('john@rest.com');
        $user2->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $user2,
                    '123456789'
                )
            );
        $manager->persist($user2);

        $microPost = new MicroPost();
        $microPost->setTitle('Welcome to Poland');
        $microPost->setText('Welcome to Poland');
        $microPost->setCreated(new \DateTime());
        $microPost->setAuthor($user2);
        $manager->persist($microPost);

        $manager->flush();
    }
}
