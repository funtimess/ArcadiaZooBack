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
    #[Route('/avis/new', name: 'avis_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $avis = new Avis();
        $form = $this->createForm(AvisType::class, $avis);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avis->setCreatedAt(new \DateTime());
            $avis->setIsApproved(false); // les avis doivent etre approuvés par un employé
            $entityManager->persist($avis);
            $entityManager->flush();

            return $this->redirectToRoute('avis_success');
        }

        //  twig
        return $this->render('avis/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
