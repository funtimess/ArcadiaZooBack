<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Repository\AvisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AvisController extends AbstractController
{
    #[Route('/api/avis', name: 'avis_index', methods: ['GET'])]
    public function index(AvisRepository $avisRepository): JsonResponse
    {
        $avisList = $avisRepository->findBy(['isVisible' => true]);
        $response = array_map(function ($avis) {
            return [
                'id' => $avis->getId(),
                'pseudo' => $avis->getPseudo(),
                'commentaire' => $avis->getCommentaire(),
                'isVisible' => $avis->isVisible(),
            ];
        }, $avisList);

        return new JsonResponse($response);
    }

    #[Route('/api/avis/new', name: 'avis_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        $avis = new Avis();
        $avis->setPseudo($data['pseudo']);
        $avis->setCommentaire($data['commentaire']);
        $avis->setVisible(false); // Avis soumis mais pas encore validé

        $entityManager->persist($avis);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Avis créé avec succès'], JsonResponse::HTTP_CREATED);
    }
}

