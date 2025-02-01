<?php

namespace App\Controller\Auth;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class Register extends AbstractController
{
    #[Route('/api/register', name: 'register', methods: ['POST'])]
    public function __invoke(
        Request                     $request,
        UserRepository              $userRepository,
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['username']) || empty($data['password'])) {
            return new JsonResponse(['error' => 'Username and password are required.'], 400);
        }

        $user = new User();

        $user->setUsername($data['username']);
        $user->setPassword($passwordHasher->hashPassword($user, $data['password']));

        if ($userRepository->count([]) === 0) {
            $user->setApproved(true);
        }

        try {
            $userRepository->save($user, true);

            return new JsonResponse([
                'created' => true,
                'message' => 'User successfully registered.',
            ], 201);
        } catch (UniqueConstraintViolationException $e) {
            return new JsonResponse([
                'error' => true,
                'message' => 'Username already taken.',
            ], 409);
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => true,
                'message' => 'An unexpected error occurred.',
            ], 500);
        }
    }
}
