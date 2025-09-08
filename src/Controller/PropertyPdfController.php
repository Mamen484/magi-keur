<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Repository\PropertyRepository;
use App\Service\PdfGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

final class PropertyPdfController extends AbstractController
{
    public function __construct(private PropertyRepository $propertyRepository, private PdfGenerator $pdfGenerator) {}

    public function __invoke(int $id): Response
    {
        $property = $this->propertyRepository->find($id);

        if (!$property) {
            throw new NotFoundHttpException('Propriété non trouvée');
        }

        $html = "<h1>{$property->getTitle()}</h1>";
        $html .= "<p><strong>Type :</strong> " . $property->getType()->value . "</p>";
        $html .= "<p><strong>Statut :</strong> " . $property->getStatus()->value . "</p>";
        $html .= "<p><strong>Ville :</strong> " . $property->getCity() . "</p>";
        $html .= "<p><strong>Prix :</strong> " . number_format($property->getPrice(), 0, ',', ' ') . " FCFA</p>";
        $html .= "<p><strong>Surface :</strong> " . $property->getSurface() . " m²</p>";
        $html .= "<p><strong>Description :</strong><br>" . nl2br($property->getDescription()) . "</p>";

        $pdf = $this->pdfService->generate($html);

        return new Response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename=fiche_propriete_{$id}.pdf'
        ]);
    }

    #[Route('/property/pdf', name: 'app_property_pdf')]
    public function index(): Response
    {
        return $this->render('property_pdf/index.html.twig', [
            'controller_name' => 'PropertyPdfController',
        ]);
    }
}
