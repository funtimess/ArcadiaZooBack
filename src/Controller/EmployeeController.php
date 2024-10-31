<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Repository\AvisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EmployeeController extends AbstractController
{
    // Liste des avis en attente de validation
    #[Route('/api/employee/avis/pending', name: 'employee_pending_avis', methods: ['GET'])]
    public function pendingAvis(AvisRepository $avisRepository): JsonResponse
    {
        $pendingAvis = $avisRepository->findBy(['isVisible' => false]);
        $response = array_map(fn($avis) => [
            'id' => $avis->getId(),
            'pseudo' => $avis->getPseudo(),
            'commentaire' => $avis->getCommentaire(),
        ], $pendingAvis);

        return new JsonResponse($response);
    }

    // Validation d'un avis spécifique
    #[Route('/api/employee/avis/{id}/validate', name: 'employee_validate_avis', methods: ['POST'])]
    public function validateAvis(int $id, AvisRepository $avisRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $avis = $avisRepository->find($id);

        if (!$avis) {
            return new JsonResponse(['error' => 'Avis non trouvé'], JsonResponse::HTTP_NOT_FOUND);
        }

        $avis->setVisible(true); // Valide l'avis pour qu'il soit visible
        $entityManager->flush();

        return new JsonResponse(['message' => 'Avis validé avec succès']);
    }

    // Invalidation d'un avis spécifique
    #[Route('/api/employee/avis/{id}/invalidate', name: 'employee_invalidate_avis', methods: ['POST'])]
    public function invalidateAvis(int $id, AvisRepository $avisRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $avis = $avisRepository->find($id);

        if (!$avis) {
            return new JsonResponse(['error' => 'Avis non trouvé'], JsonResponse::HTTP_NOT_FOUND);
        }

        $avis->setVisible(false); // Rend l'avis non visible
        $entityManager->flush();

        return new JsonResponse(['message' => 'Avis invalidé avec succès']);
    }
}

