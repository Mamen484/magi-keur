<?php

namespace App\Controller;

use App\Repository\DocumentRequestRepository;
use App\Service\PdfGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

final class DocumentRequestPdfController extends AbstractController
{
    public function __construct(private DocumentRequestRepository $repository, private PdfGenerator $pdfGenerator) {}

    public function __invoke(int $id, Request $request): Response
    {
        $document = $this->repository->find($id);

        if (!$document) {
            throw new NotFoundHttpException("DocumentRequest not found.");
        }

        // 🔧 À adapter : tu peux créer un template Twig si tu préfères
        $html = <<<HTML
            <h1>Demande de document juridique #{$document->getId()}</h1>
            <p><strong>Type :</strong> {$document->getType()}</p>
            <p><strong>Contenu :</strong></p>
            <p>{$document->getContent()}</p>
        HTML;

        $pdf = $this->pdfGenerator->generate($html);

        return new Response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="legal_document_{$document->getId()}.pdf"',
        ]);
    }

    #[Route('/legal/document/request/pdf', name: 'app_legal_document_request_pdf')]
    public function index(): Response
    {
        return $this->render('legal_document_request_pdf/index.html.twig', [
            'controller_name' => 'DocumentRequestPdfController',
        ]);
    }
}
