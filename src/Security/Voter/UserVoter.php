<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;


final class UserVoter extends Voter
{
    public const LIST = 'USER_LIST';
    public const APPROVE = 'USER_APPROVE';
    public const REVOKE = 'USER_REVOKE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::LIST, self::APPROVE, self::REVOKE]);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        return match ($attribute) {
            self::LIST => true,
            self::APPROVE, self::REVOKE => $user->isApproved() && $user->getId() !== $subject->getId(),
            default => false,
        };
    }
}
