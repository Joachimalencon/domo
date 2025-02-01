<?php

namespace App\Controller\Auth;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\Voter\UserVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class Revoke extends AbstractController
{
    #[Route('/api/users/{id}/revoke', name: 'revoke', methods: ['POST'])]
    #[IsGranted(UserVoter::REVOKE, subject: 'user')]
    public function __invoke(User $user, UserRepository $userRepository): JsonResponse
    {
        $admin = $this->getUser();
        if (!$admin instanceof User) {
            return new JsonResponse(['error' => 'Unauthorized'], 401);
        }

        if (!$user->isApproved()) {
            return new JsonResponse(['error' => 'User is already unapproved'], 400);
        }

        $user->setApproved(false);
        $user->setApprovedBy($admin);

        $userRepository->save($user, true);

        return new JsonResponse(['message' => 'User successfully revoked'], 200);
    }
}
