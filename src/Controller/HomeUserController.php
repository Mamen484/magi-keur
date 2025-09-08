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
final class HomeUserController extends AbstractController
{
    /**
     * Affiche la page d'accueil avec les propriétés, les avis et les fournisseurs.
     */
    #[Route('/homepage', name: 'homepage_index', defaults: ['page' => '1', '_format' => 'html'], methods: ['GET'])]
    #[Route('/rss.xml', name: 'homepage_rss', defaults: ['page' => '1', '_format' => 'xml'], methods: ['GET'])]
    #[Route('/page/{page}', name: 'home_index_paginated', defaults: ['_format' => 'html'], requirements: ['page' => Requirement::POSITIVE_INT], methods: ['GET'])]
    #[Cache(smaxage: 10)]
    public function index(
        Request $request,
        PropertyRepository $propertyRepository,
        ReviewRepository $reviewRepository
    ): Response {
        // Moteur de recherche : récupération des critères
        $city = $request->query->get('city');
        $category = $request->query->get('category');
        $date = $request->query->get('date');
        $createdAtDate = null;

        if ($date) {
            try {
                $createdAtDate = new \DateTimeImmutable($date);
            } catch (\Exception $e) {
                // Optionnel : logger l'erreur, ignorer la date si invalide
                $createdAtDate = null;
            }
        }

        // Rechercher les offres à la une selon critères (simplifié ici)
        $featuredProperties = $propertyRepository->findFeatured($city, $category, $createdAtDate);

        // Prestataires populaires (ex: les 5 avec le plus de réservations ou meilleurs avis)
        $popularProviders = $propertyRepository->findPopularProperties(10);

        // Derniers avis
        $latestReviews = $reviewRepository->findLatest(5);

        $format = $request->get('_format');
        dd($popularProviders);
        return $this->render('home/index.html.twig', [
            'featuredProperties' => $featuredProperties,
            'popularProviders' => $popularProviders,
            'latestReviews' => $latestReviews,
            'format' => $format,
        ]);
    }
}
