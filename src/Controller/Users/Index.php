<?php

namespace App\Controller\Users;

use App\Repository\UserRepository;
use App\Security\Voter\UserVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class Index extends AbstractController
{
    #[Route('/api/users', name: 'get_users', methods: ['GET'])]
    #[IsGranted(UserVoter::LIST)]
    public function __invoke(Request $request, UserRepository $userRepository): JsonResponse
    {
        $approvedFilter = $request->query->get('approved');

        $users = match ($approvedFilter) {
            'true' => $userRepository->findBy(['approved' => true]),
            'false' => $userRepository->findBy(['approved' => false]),
            default => $userRepository->findAll(),
        };

        return $this->json($users, Response::HTTP_OK, [], ['groups' => 'me']);
    }
}
