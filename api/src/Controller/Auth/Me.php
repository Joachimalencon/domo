<?php

namespace App\Controller\Auth;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class Me extends AbstractController
{
    #[Route('/api/me', name: 'me', methods: ['GET'])]
    public function __invoke(AuthenticationUtils $authenticationUtils, TokenInterface $token): JsonResponse
    {
        return $this->json($token->getUser(), Response::HTTP_OK, [], ['groups' => 'me']);
    }
}