<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiService
{
    public function __construct(protected readonly ValidatorInterface $validator)
    {
    }

    /**
     * JSON response. With a single message or an array.
     *
     * @param  string|array  $data
     * @param  int  $statusCode
     *
     * @return JsonResponse
     */
    public function respond(string|array $data, int $statusCode): JsonResponse
    {
        if (is_string($data)) {
            $data = ['message' => $data];
        }

        return new JsonResponse($data, $statusCode);
    }

    /**
     * JSON response with an error. A single message or an errors array.
     *
     * @param  string|array  $errors
     * @param  int  $statusCode
     * @param  array  $headers
     *
     * @return JsonResponse
     */
    public function respondWithErrors(string|array $errors, int $statusCode, array $headers = []): JsonResponse
    {
        $data = [
            'errors' => $errors,
        ];

        if (is_string($errors)) {
            $data = [
                'errors' => [
                    'message' => $errors
                ]
            ];
        }

        return new JsonResponse($data, $statusCode, $headers);
    }

    /**
     * Changes request params keys to an actual user attributes ones.
     *
     * @param  array  $params
     *
     * @return array
     */
    public function userRequestParamsToAttributes(array $params): array {
        $params['firstName'] = $params['first_name'];
        $params['lastName'] = $params['last_name'];

        return $params;
    }

    /**
     * Gets a user array for a response.
     *
     * @param  \App\Entity\User  $user
     *
     * @return array
     */
    public function getUserResponseData(User $user): array {
        return [
            'id' => $user->getId(),
            'first_name' => $user->getFirstName(),
            'last_name' => $user->getLastName(),
            'email' => $user->getEmail(),
            'phone' => $user->getPhone(),
        ];
    }

    /**
     * Validates attributes and returns an array of errors for a response.
     *
     * @param  mixed  $entity
     *
     * @return array
     */
    public function validateEntity(mixed $entity): array
    {
        $errors = [];
        $constraintViolationList = $this->validator->validate($entity);
        if (count($constraintViolationList) > 0) {
            foreach ($constraintViolationList as $constraintViolation) {
                $errors[] = [
                    'property' => $constraintViolation->getPropertyPath(),
                    'message' => $constraintViolation->getMessage(),
                ];
            }
        }

        return $errors;
    }

    /**
     * Transforms the body of a json request to retrieve parameters.
     *
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     *
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function transformJsonBody(Request $request): Request
    {
        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            return $request;
        }

        $request->request->replace($data);

        return $request;
    }
}