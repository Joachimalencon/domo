<?php

namespace App\Controller\Dockers\Containers;

use App\Security\Voter\ContainerVoter;
use App\Service\DockerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class Stop extends AbstractController
{
    public function __construct(private readonly DockerService $dockerService)
    {
    }

    #[Route('/api/dockers/containers/{id}/stop', name: 'stop_containers', methods: ['POST'])]
    #[IsGranted(ContainerVoter::STOP)]
    public function __invoke(string $id): JsonResponse
    {
        $containers = $this->dockerService->stopContainer($id);
        return $this->json($containers);
    }
}
