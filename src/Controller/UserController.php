<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\ApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{

    /**
     * Create a user.
     *
     * @param  Request  $request
     * @param  \App\Repository\UserRepository  $userRepository
     * @param  \App\Service\ApiService  $apiService
     *
     * @return JsonResponse
     */
    #[Route('/api/users', name: 'create_user', methods: ["POST"])]
    public function createUserResponse(Request $request, UserRepository $userRepository, ApiService $apiService): JsonResponse
    {
        $request = $apiService->transformJsonBody($request);
        $params = $request->request->all();
        $attributes = $apiService->userRequestParamsToAttributes($params);

        $user = $userRepository->create($attributes);

        $errors = $apiService->validateEntity($user);
        if (!empty($errors)) {
            return $apiService->respondWithErrors($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $userRepository->save($user, true);

        return $apiService->respond('User was created.', Response::HTTP_CREATED);
    }

    /**
     * Get all users.
     *
     * @param  \App\Repository\UserRepository  $userRepository
     * @param  \App\Service\ApiService  $apiService
     *
     * @return JsonResponse
     */
    #[Route('/api/users/', name: 'get_users', methods: ["GET"])]
    public function getUsersResponse(
        UserRepository $userRepository,
        ApiService $apiService
    ): JsonResponse
    {
        $users = $userRepository->findAll();
        $data = [];
        foreach ($users as $user) {
            $data[] = $apiService->getUserResponseData($user);
        }

        return $apiService->respond($data, Response::HTTP_OK);
    }

    /**
     * Get a user.
     *
     * @param  string  $id
     * @param  \App\Repository\UserRepository  $userRepository
     * @param  \App\Service\ApiService  $apiService
     *
     * @return JsonResponse
     */
    #[Route('/api/users/{id}', name: 'get_user', methods: ["GET"])]
    public function getUserResponse(string $id, UserRepository $userRepository, ApiService $apiService): JsonResponse
    {
        $user = $userRepository->findOneBy(['id' => $id]);

        if (empty($user)) {
            return $apiService->respondWithErrors('No such user.', Response::HTTP_NOT_FOUND);
        }

        $data = $apiService->getUserResponseData($user);

        return $apiService->respond($data, Response::HTTP_OK);
    }

    /**
     * Edit a user.
     *
     * @param  string  $id
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     * @param  \App\Repository\UserRepository  $userRepository
     * @param  \App\Service\ApiService  $apiService
     *
     * @return JsonResponse
     */
    #[Route('/api/users/{id}', name: 'edit_user', methods: ["PATCH"])]
    public function editUserResponse(
        string $id,
        Request $request,
        UserRepository $userRepository,
        ApiService $apiService
    ): JsonResponse
    {
        $currentUser = $this->getUser();
        if (!$currentUser->isAdmin() && $currentUser->getId() != $id) {
            return $apiService->respondWithErrors('Access forbidden.', Response::HTTP_FORBIDDEN);
        }

        $requestedUser = $userRepository->findOneBy(['id' => $id]);
        if (empty($requestedUser)) {
            return $apiService->respondWithErrors('No such user.', Response::HTTP_NOT_FOUND);
        }

        $request = $apiService->transformJsonBody($request);
        $params = $request->request->all();
        $attributes = $apiService->userRequestParamsToAttributes($params);

        $requestedUser = $userRepository->update($requestedUser, $attributes);

        $errors = $apiService->validateEntity($requestedUser);
        if (!empty($errors)) {
            return $apiService->respondWithErrors($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $userRepository->save($requestedUser, true);

        return $apiService->respond('User was edited.', Response::HTTP_OK);
    }

    /**
     * Delete a user.
     *
     * @param  string  $id
     * @param  \App\Repository\UserRepository  $userRepository
     * @param  \App\Service\ApiService  $apiService
     *
     * @return JsonResponse
     */
    #[Route('/api/users/{id}', name: 'delete_user', methods: ["DELETE"])]
    public function deleteUserResponse(string $id, UserRepository $userRepository, ApiService $apiService): JsonResponse
    {
        $user = $userRepository->findOneBy(['id' => $id]);

        if (empty($user)) {
            return $apiService->respondWithErrors('No such user.', Response::HTTP_NOT_FOUND);
        }

        $userRepository->remove($user, true);

        return $apiService->respond('User was deleted.', Response::HTTP_OK);
    }
}
