<?php

namespace App\Controller\Dockers\Containers;

use App\Service\DockerService;
use App\Security\Voter\ContainerVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class Index extends AbstractController
{
    public function __construct(private readonly DockerService $dockerService)
    {
    }

    #[Route('/api/dockers/containers', name: 'get_containers', methods: ['GET'])]
    #[IsGranted(ContainerVoter::LIST)]
    public function __invoke(): JsonResponse
    {
        $containers = $this->dockerService->listContainers();
        return $this->json($containers);
    }
}
