<?php

namespace App\Controller;

use App\Service\RouteService;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class RoutingController extends AbstractController
{
    private RouteService $routeService;

    public function __construct(RouteService $routeService)
    {
        $this->routeService = $routeService;
    }

    #[Route('/routing/{origin}/{destination}', name: 'routing', methods: ['POST'])]
    public function index(string $origin, string $destination): JsonResponse
    {
        try {
            $route = $this->routeService->calculateRoute($origin, $destination);
        } catch (InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }

        if ($route === null) {
            return $this->json(['error' => 'No land route found'], 400);
        }

        return $this->json([
            'route' => $route,
        ]);
    }
}
