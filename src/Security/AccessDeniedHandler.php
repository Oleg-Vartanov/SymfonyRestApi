<?php

namespace App\Security;

use App\Service\ApiService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    public function __construct(private readonly ApiService $apiService)
    {
    }

    public function handle(
        Request $request,
        AccessDeniedException $accessDeniedException
    ): ?Response {
        return $this->apiService->respondWithErrors('Access Denied.', Response::HTTP_FORBIDDEN);
    }
}