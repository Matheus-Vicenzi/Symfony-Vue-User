<?php

namespace App\Controller;

use App\DTOs\LoginInputDTO;
use App\Service\AuthService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/auth/v1')]
class AuthController extends BaseController
{
    public function __construct(private SerializerInterface $serializer, private AuthService $authService) 
    {
        parent::__construct($serializer);
    }

    #[Route('/login', name: 'api_auth_login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $loginDTO = $this->deserialize($request->getContent(), LoginInputDTO::class);
        $sessionDTO = $this->authService->authenticate($loginDTO);
        return $this->json($sessionDTO);
    }

    #[Route('/logout', name: 'api_auth_logout', methods: ['POST'])]
    public function logout(): JsonResponse
    {
        $this->authService->logout();

        return $this->json([
            'success' => true,
            'message' => 'Logout realizado com sucesso.'
        ]);
    }
}