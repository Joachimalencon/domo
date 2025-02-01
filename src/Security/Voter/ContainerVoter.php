<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class ContainerVoter extends Voter
{
    public const LIST = 'CONTAINER_LIST';
    public const START = 'CONTAINER_START';
    public const STOP = 'CONTAINER_STOP';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::LIST, self::START, self::STOP]);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        return match ($attribute) {
            self::LIST => true,
            self::START, self::STOP => $user->isApproved(),
            default => false,
        };
    }
}
