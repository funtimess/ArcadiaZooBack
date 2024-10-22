<?php

namespace App\Controller;

use App\Repository\ConsultationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractController
{
    #[Route('/api/admin/dashboard', name: 'admin_dashboard', methods: ['GET'])]
    public function index(ConsultationRepository $consultationRepository): JsonResponse
    {
        
        $consultations = $consultationRepository->findAll();
        $totalConsultations = count($consultations);  //  toute autre logique de calcul

        $data = [
            'consultations' => $totalConsultations,
        ];

        return new JsonResponse($data);
    }
}
