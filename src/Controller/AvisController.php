<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Form\AvisType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AvisController extends AbstractController
{
    #[Route('/api/avis/new', name: 'avis_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
    
        if (empty($data['pseudo']) || empty($data['commentaire'])) {
            return new JsonResponse(['error' => 'Les champs pseudo et commentaire sont requis.'], Response::HTTP_BAD_REQUEST);
        }
    
        $avis = new Avis();
        $avis->setPseudo($data['pseudo']);
        $avis->setCommentaire($data['commentaire']);
        $avis->setCreatedAt(new \DateTime());
        $avis->setIsApproved(false); // les avis doivent être approuvés par un employé
    
        $entityManager->persist($avis);
        $entityManager->flush();
    
        return new JsonResponse(['message' => 'Avis créé avec succès.'], Response::HTTP_CREATED);
    }
    
}
