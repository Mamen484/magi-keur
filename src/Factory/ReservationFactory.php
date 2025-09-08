<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Factory;

use App\Entity\Reservation;
use App\Enum\ReservationStatus;
use App\Enum\ReservationType;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Reservation>
 */
final class ReservationFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    public static function class(): string
    {
        return Reservation::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'bookableId' => self::faker()->randomNumber(),
            'createdAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'currency' => self::faker()->text(3),
            'endDate' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'startDate' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'status' => self::faker()->randomElement(ReservationStatus::cases()),
            'totalAmount' => self::faker()->randomFloat(),
            'type' => self::faker()->randomElement(ReservationType::cases()),
            'updatedAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'user' => UserFactory::new(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Reservation $reservation): void {})
        ;
    }
}
