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

use App\Entity\Property;
use App\Enum\PropertyStatus;
use App\Enum\PropertyType;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Property>
 */
final class PropertyFactory extends PersistentProxyObjectFactory
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
        return Property::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'address' => AddressFactory::new(),
            'company' => CompanyFactory::new(),
            'createdAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'currency' => self::faker()->text(3),
            'isVisible' => self::faker()->boolean(),
            'owner' => UserFactory::new(),
            'price' => self::faker()->randomFloat(),
            'status' => self::faker()->randomElement(PropertyStatus::cases()),
            'surface' => self::faker()->randomFloat(),
            'title' => self::faker()->text(255),
            'type' => self::faker()->randomElement(PropertyType::cases()),
            'updatedAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Property $property): void {})
        ;
    }
}
