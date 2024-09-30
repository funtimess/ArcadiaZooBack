<?php

namespace App\Controller;

use App\Entity\Service;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{
    #[Route('/api/services', name: 'service_index', methods: ['GET'])]
    public function index(ServiceRepository $serviceRepository): JsonResponse
    {
        $services = $serviceRepository->findAll();
        $servicesArray = [];

        foreach ($services as $service) {
            $servicesArray[] = [
                'id' => $service->getId(),
                'name' => $service->getNom(),
                'description' => $service->getDescription()
            ];
        }

        return new JsonResponse($servicesArray, Response::HTTP_OK);
    }

    #[Route('/api/services/new', name: 'service_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $service = new Service();
        $service->setNom($data['name']);
        $service->setDescription($data['description']);

        $entityManager->persist($service);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Service créé avec succès.'], Response::HTTP_CREATED);
    }

    #[Route('/api/services/{id}', name: 'service_delete', methods: ['DELETE'])]
    public function delete(Service $service, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($service);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Service supprimé avec succès.'], Response::HTTP_OK);
    }
}
