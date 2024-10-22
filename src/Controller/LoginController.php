<?php
// src/Controller/LoginController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

// src/Controller/LoginController.php
#[Route('/api/login', name: 'api_login', methods: ['POST'])]
public function login(Request $request, AuthenticationUtils $authenticationUtils): JsonResponse
{
    $error = $authenticationUtils->getLastAuthenticationError();

    if ($error) {
        return new JsonResponse(['error' => 'Invalid credentials.'], JsonResponse::HTTP_UNAUTHORIZED);
    }

    // Gestion réussie de la connexion
    return new JsonResponse(['message' => 'Login successful']);
}

