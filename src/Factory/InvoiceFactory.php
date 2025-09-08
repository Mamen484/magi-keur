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

use App\Entity\Invoice;
use App\Enum\InvoiceStatus;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Invoice>
 */
final class InvoiceFactory extends PersistentProxyObjectFactory
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
        return Invoice::class;
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
            'invoiceNumber' => self::faker()->text(80),
            'issueDate' => null, // TODO add DATETIMETZ type manually
            'locale' => self::faker()->text(10),
            'status' => self::faker()->randomElement(InvoiceStatus::cases()),
            'totalAmount' => self::faker()->randomFloat(),
            'totalTaxes' => self::faker()->randomFloat(),
            'updatedAt' => null, // TODO add DATETIMETZ type manually
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Invoice $invoice): void {})
        ;
    }
}
