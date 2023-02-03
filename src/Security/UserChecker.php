<?php

namespace App\Security;

use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface {

    /**
     * @param User $user
     */
    public function checkPreAuth(UserInterface $user)
    {
        if ($user->getBannedUntil() === null) {
            return;
        }

        $now = new \DateTime();

        if ($now < $user->getBannedUntil()) {
            throw new AccessDeniedException('The user is banned');
        }
    }

    /**
     * @param User $user
     */
    public function checkPostAuth(UserInterface $user)
    {
        // TODO: Implement checkPostAuth() method.
    }
}