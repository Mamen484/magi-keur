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

use App\Repository\InvoiceRepository;
use App\Service\PdfGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

final class InvoicePdfController extends AbstractController
{
    public function __construct(private InvoiceRepository $invoiceRepository, private PdfGenerator $pdfGenerator) {}

    public function __invoke(int $id): Response
    {
        $invoice = $this->invoiceRepository->find($id);

        if (!$invoice) {
            throw new NotFoundHttpException("Invoice not found.");
        }

        // Construire le HTML pour DomPDF (exemple simple)
        $html = '<h1>Facture #' . $invoice->getId() . '</h1>';
        $html .= '<p><strong>Client:</strong> ' . $invoice->getClient()->getName() . '</p>';
        $html .= '<p><strong>Montant total:</strong> ' . number_format($invoice->getAmount(), 2, ',', ' ') . ' €</p>';
        $html .= '<p><strong>Statut:</strong> ' . $invoice->getStatus()->value . '</p>';
        $html .= '<p><strong>Date d\'échéance:</strong> ' . $invoice->getDueDate()->format('d/m/Y') . '</p>';
        $html .= '<hr><h3>Détails :</h3><ul>';

        foreach ($invoice->getServiceItems() as $item) {
            $html .= '<li>' . $item->getDescription() . ' - ' . number_format($item->getAmount(), 2, ',', ' ') . ' €</li>';
        }

        $html .= '</ul>';

        $pdf = $this->pdfGenerator->generate($html);

        return new Response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="invoice_' . $invoice->getId() . '.pdf"',
        ]);
    }

    #[Route('/invoice/pdf', name: 'app_invoice_pdf')]
    public function index(): Response
    {
        return $this->render('invoice_pdf/index.html.twig', [
            'controller_name' => 'InvoicePdfController',
        ]);
    }
}
