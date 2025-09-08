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

use App\Entity\Subscription;
use App\Enum\SubscriptionStatus;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Subscription>
 */
final class SubscriptionFactory extends PersistentProxyObjectFactory
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
        return Subscription::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'company' => CompanyFactory::new(),
            'createdAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'currency' => self::faker()->text(3),
            'endsAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'interval' => self::faker()->text(20),
            'plan' => SubscriptionPlanFactory::new(),
            'startedAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'status' => self::faker()->randomElement(SubscriptionStatus::cases()),
            'updatedAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Subscription $subscription): void {})
        ;
    }
}
