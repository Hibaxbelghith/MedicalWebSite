<?php

    namespace App\Security;

    use App\Entity\User;
    use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
    use Symfony\Component\Security\Core\User\UserCheckerInterface;
    use Symfony\Component\Security\Core\User\UserInterface;

    class UserChecker implements UserCheckerInterface
    {
    public function checkPreAuth(UserInterface $user): void
    {
    if (!$user instanceof User) {
    return;
    }

    if ($user->isBlocked()) {
    throw new CustomUserMessageAuthenticationException('Votre compte est bloqué.');
    }
    }

    public function checkPostAuth(UserInterface $user): void
    {
    // Optionnel : Logique supplémentaire après l'authentification
    }
    }
