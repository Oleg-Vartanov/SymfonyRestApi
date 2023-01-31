<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AuthController extends ApiController
{
    /**
     * @param  Request  $request
     * @param  \Doctrine\Persistence\ManagerRegistry  $doctrine
     * @param  \Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface  $passwordHasher
     *
     * @return JsonResponse
     */
    #[Route('/api/register', name: 'register', methods: ["POST"])]
    public function register(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $request = $this->transformJsonBody($request);
        $email = $request->get('email');
        $password = $request->get('password');
        $firstName = $request->get('first_name');
        $lastName = $request->get('last_name');

        if (empty($email) || empty($password) || empty($firstName) || empty($lastName)) {
            return $this->respondValidationError("Invalid data");
        }

        $user = new User();
        $user->setEmail($email);
        $hashedPassword = $passwordHasher->hashPassword(
          $user,
          $password
        );
        $user->setPassword($hashedPassword);
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $em = $doctrine->getManager();
        $em->persist($user);
        $em->flush();

        return $this->respondWithSuccess('User was created');
    }
}
