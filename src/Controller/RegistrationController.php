<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/api/registration', name: 'app_register', methods: ['POST'])]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        // verifie si les champs sont present
        if (empty($data['username']) || empty($data['password']) || empty($data['nom']) || empty($data['prenom']) || empty($data['role'])) {
            return new JsonResponse(['error' => 'Les champs username, password, nom, prenom et role sont requis.'], Response::HTTP_BAD_REQUEST);
        }

        // verifie si le role est valide admin, employee, vet
        if (!in_array($data['role'], ['ROLE_ADMIN', 'ROLE_EMPLOYEE', 'ROLE_VET'])) {
            return new JsonResponse(['error' => 'Rôle invalide. Seuls les rôles admin, employee, et vet sont autorisés.'], Response::HTTP_BAD_REQUEST);
        }

        // créer un nouvel utilisateur
        $utilisateur = new Utilisateur();
        $utilisateur->setUsername($data['username']);
        $utilisateur->setNom($data['nom']);
        $utilisateur->setPrenom($data['prenom']);
        
        // hach le mot de passe
        $hashedPassword = $passwordHasher->hashPassword($utilisateur, $data['password']);
        $utilisateur->setPassword($hashedPassword);

        // attribuer le rôle choisi admin, employee, vet
        $utilisateur->setRoles([$data['role']]);

        // sauvegarde utilisateur en base de donnees
        $entityManager->persist($utilisateur);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Utilisateur créé avec succès.'], Response::HTTP_CREATED);
    }
}