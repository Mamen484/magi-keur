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

use App\Entity\TimeSlot;
use App\Enum\ReservationType;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<TimeSlot>
 */
final class TimeSlotFactory extends PersistentProxyObjectFactory
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
        return TimeSlot::class;
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
            'endTime' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'exception' => self::faker()->boolean(),
            'isRecurring' => self::faker()->boolean(),
            'startTime' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'type' => self::faker()->randomElement(ReservationType::cases()),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(TimeSlot $timeSlot): void {})
        ;
    }
}
