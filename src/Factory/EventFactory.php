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

use App\Entity\Event;
use App\Enum\EventStatus;
use App\Enum\EventType;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Event>
 */
final class EventFactory extends PersistentProxyObjectFactory
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
        return Event::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'createdAt' => null, // TODO add DATETIMETZ type manually
            'currency' => self::faker()->text(3),
            'startDatetime' => null, // TODO add DATETIMETZ type manually
            'status' => self::faker()->randomElement(EventStatus::cases()),
            'title' => self::faker()->text(255),
            'type' => self::faker()->randomElement(EventType::cases()),
            'updatedAt' => null, // TODO add DATETIMETZ type manually
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Event $event): void {})
        ;
    }
}
