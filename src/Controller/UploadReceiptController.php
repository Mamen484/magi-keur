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
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UploadReceiptController extends AbstractController
{
    #[Route('/api/rent_payments/{id}/upload_receipt', name: 'upload_receipt', methods: ['POST'])]
    public function __invoke(
        int $id,
        Request $request,
        RentPaymentRepository $repo,
        FileUploader $uploader,
        EntityManagerInterface $em
    ): Response {
        $rentPayment = $repo->find($id);
        if (!$rentPayment) {
            return new JsonResponse(['error' => 'RentPayment not found'], Response::HTTP_NOT_FOUND);
        }

        $file = $request->files->get('file');
        if (!$file) {
            return new JsonResponse(['error' => 'No file uploaded'], Response::HTTP_BAD_REQUEST);
        }

        $path = $uploader->upload($file);
        $rentPayment->setReceiptPath($path);
        $em->flush();

        return new JsonResponse(['receipt_path' => $path], Response::HTTP_OK);
    }

    #[Route('/upload/receipt', name: 'app_upload_receipt')]
    public function index(): Response
    {
        return $this->render('upload_receipt/index.html.twig', [
            'controller_name' => 'UploadReceiptController',
        ]);
    }
}
