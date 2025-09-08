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

use App\Repository\RentPaymentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Attribute\Route;

final class DownloadReceiptController extends AbstractController
{
    #[Route('/api/rent_payments/{id}/receipt', name: 'download_receipt', methods: ['GET'])]
    public function __invoke(int $id, RentPaymentRepository $repo): BinaryFileResponse
    {
        $payment = $repo->find($id);

        if (!$payment || !$payment->getReceiptPath()) {
            throw $this->createNotFoundException('Receipt not found.');
        }

        $filepath = $this->getParameter('kernel.project_dir') . '/public' . $payment->getReceiptPath();

        return (new BinaryFileResponse($filepath))
            ->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, basename($filepath));
    }

    #[Route('/download/receipt', name: 'app_download_receipt')]
    public function index(): Response
    {
        return $this->render('download_receipt/index.html.twig', [
            'controller_name' => 'DownloadReceiptController',
        ]);
    }
}
