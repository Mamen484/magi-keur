<?php

namespace App\Controller;

use App\Repository\PropertyRepository;
use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

/**
 * Contrôleur de la page d'accueil du site immobilier.
 */
final class HomeController extends AbstractController
{
    #[Route('/home', name: 'home_index', defaults: ['page' => 1, '_format' => 'html'], methods: ['GET'])]
    #[Route('/page/{page}', name: 'home_index_paginated', defaults: ['_format' => 'html'], requirements: ['page' => Requirement::POSITIVE_INT], methods: ['GET'])]
    #[Cache(smaxage: 10)]
    public function index(Request $request, PropertyRepository $propertyRepository): Response
    {
        $city = $request->query->get('city');
        $type = $request->query->get('type');
        $maxPriceRaw = $request->query->get('max_price');
        $maxPrice = is_numeric($maxPriceRaw) ? (float) $maxPriceRaw : null;
        $startDate = $request->query->get('start_date') ? new \DateTime($request->query->get('start_date')) : null;
        $endDate = $request->query->get('end_date') ? new \DateTime($request->query->get('end_date')) : null;

        // Rechercher les offres à la une selon critères
        $properties = $propertyRepository->searchProperties($city, $type, $maxPrice, $startDate, $endDate);
        $popularProperties = $propertyRepository->findTopByReservations();
        $topRatedProperties = $propertyRepository->findTopByRating();

        // Si c'est une requête AJAX, on renvoie juste le fragment HTML
        if ($request->isXmlHttpRequest()) {
            return $this->render('home/_properties_list.html.twig', [
                'properties' => $properties
            ]);
        }
dd($topRatedProperties);
        // Sinon on charge la page complète
        return $this->render('home/index.html.twig', [
            'properties' => $properties,
            'popularProperties' => $popularProperties,
            'bestRatedProperties' => $topRatedProperties,
        ]);
    }
}
