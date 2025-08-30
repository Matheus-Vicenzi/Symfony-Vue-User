<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Service\UserService;
use App\DTOs\UserInputDTO;
use App\DTOs\UserOutputDTO;
use App\Service\AuthService;
use Psr\Log\LoggerInterface;

#[Route('/api/user/v1')]
class UserController extends BaseController
{
    public function __construct(
        private SerializerInterface $serializer, 
        private UserService $userService, 
        private AuthService $authService,
        private LoggerInterface $logger
    )
    {
        parent::__construct($serializer);
    }

    #[Route('/{id}', name: 'app_user_get', methods: ['GET'])]
    public function index(int $id): JsonResponse
    {
        $currentUser = $this->authService->getCurrentUser();
        $this->logger->info('Current user: ', ['user' => $currentUser]);

        if (!$currentUser) {
            return $this->json(['error' => 'Não autenticado'], 401);
        }

        if ($currentUser['id'] !== $id) {
            return $this->json(['error' => 'Acesso negado'], 403);
        }

        try {
            $user = $this->userService->findUserById($id);
        } catch (\Exception $e) {
            $this->logger->error('Erro ao buscar usuário', ['exception' => $e]);
            return $this->json(['error' => 'Erro interno'], 500);
        }
        
        if (!$user) {
            return $this->json(['error' => 'User not found'], 404);
        }

        $userOutputDTO = new UserOutputDTO(
            $user->getId(),
            $user->getName(),
            $user->getEmail()
        );

        return $this->json($userOutputDTO);
    }

    #[Route('', name: 'app_user_post', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $json = $request->getContent();
        $userInputDTO = $this->deserialize($json, UserInputDTO::class);
        $user = $this->userService->createUser($userInputDTO);
        $userOutputDTO = new UserOutputDTO(
            $user->getId(),
            $user->getName(),
            $user->getEmail()
        );
        return $this->json($userOutputDTO, 201);
    }
}
