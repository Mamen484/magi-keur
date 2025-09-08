<?php

namespace App\Controller;

use App\Entity\Property;
use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class PropertyController extends AbstractController
{
    #[Route('/properties', name: 'property_index', methods: ['GET'])]
    public function index(Request $request, PropertyRepository $propertyRepository): Response
    {
        $properties = $propertyRepository->findBy(['isVisible' => true], ['createdAt' => 'DESC']);

        return $this->render('property/index.html.twig', [
            'properties' => $properties,
        ]);
    }

    #[Route('/properties/{id}', name: 'property_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Property $property): Response
    {
        if (!$property->isVisible()) {
            throw $this->createNotFoundException('Cette propriété est introuvable.');
        }

        return $this->render('property/show.html.twig', [
            'property' => $property,
        ]);
    }
}
