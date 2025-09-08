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

use App\Entity\RentPayment;
use App\Enum\PaymentMethod;
use App\Enum\PaymentStatus;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<RentPayment>
 */
final class RentPaymentFactory extends PersistentProxyObjectFactory
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
        return RentPayment::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'amount' => self::faker()->randomFloat(),
            'createdAt' => null, // TODO add DATETIMETZ type manually
            'currency' => self::faker()->text(3),
            'dueDate' => null, // TODO add DATETIMETZ type manually
            'method' => self::faker()->randomElement(PaymentMethod::cases()),
            'status' => self::faker()->randomElement(PaymentStatus::cases()),
            'tenant' => UserFactory::new(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(RentPayment $rentPayment): void {})
        ;
    }
}
