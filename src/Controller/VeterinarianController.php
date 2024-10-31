<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Entity\RapportVeterinaire;
use App\Entity\Habitat;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class VeterinarianController extends AbstractController
{
    #[Route('/api/vet/animal/{id}/rapport', name: 'add_rapport', methods: ['POST'])]
    public function addRapport(Request $request, Animal $animal, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $rapport = new RapportVeterinaire();
        $rapport->setAnimal($animal);
        $rapport->setDate(new \DateTime());
        $rapport->setDetail($data['detail']);

        $entityManager->persist($rapport);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Rapport vétérinaire ajouté'], JsonResponse::HTTP_CREATED);
    }

    #[Route('/api/vet/habitat/{id}/comment', name: 'comment_habitat', methods: ['POST'])]
    public function commentHabitat(Habitat $habitat, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $habitat->setCommentaireHabitat($data['commentaire']);

        $entityManager->flush();

        return new JsonResponse(['message' => 'Commentaire ajouté']);
    }
}
