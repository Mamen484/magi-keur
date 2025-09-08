<?php

namespace App\Service;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ReservationProcessor implements ProcessorInterface
{
    public function __construct(private ProcessorInterface $persistProcessor, private ReservationRepository $reservationRepository) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if (!$data instanceof Reservation) {
            return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
        }

        if ($data->getStartDate() >= $data->getEndDate()) {
            throw new BadRequestHttpException('La date de début doit être avant la date de fin.');
        }

        if ($this->reservationRepository->hasConflict($data->getProperty(), $data->getStartDate(), $data->getEndDate())) {
            throw new BadRequestHttpException('Ce logement est déjà réservé sur cette période.');
        }

        // Exemple très simplifié
        $stripe = new \Stripe\StripeClient($_ENV['STRIPE_API_KEY']);
        $paymentIntent = $stripe->paymentIntents->create([
            'amount' => (int) ($data->getTotalAmount() * 100),
            'currency' => 'eur',
            'payment_method_types' => ['card'],
            'metadata' => [
                'reservation_id' => $data->getId()
            ]
        ]);

        // (Étape suivante : intégration paiement Stripe ici)

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
