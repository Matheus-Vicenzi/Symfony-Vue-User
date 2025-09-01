<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api')]
class TestController extends BaseController
{
    public function __construct(SerializerInterface $serializer)
    {
        parent::__construct($serializer);
    }

    #[Route('/test', name: 'api_test', methods: ['GET'])]
    public function test(): JsonResponse
    {
        return $this->json(['ok' => true]);
    }
}
