<?php

/**
 *  Copyright (c) 2025 Rhyme Digital, LLC.
 *
 *  @link			https://rhyme.digital
 *  @license		https://www.gnu.org/licenses/lgpl-3.0.txt LGPL
 */

declare(strict_types=1);

namespace Rhymedigital\IsotopeUpsShipping\Entity\UpsApi\Entity;

use RahulGodiyal\PhpUpsApiWrapper\Entity\Package as BasePackage;

/**
 * This entity is used to allow for multiple packages in the UPS SDK.
 * At the time of this bundle's initial development, the SDK only
 * allowed for one package per shipment. AF 8/6/25
 */
class RatingPackages extends BasePackage
{
    /**
     * @var array
     */
    protected array $packages = [];

    /**
     * @return array
     */
    public function getPackages(): array
    {
        return $this->packages;
    }

    /**
     * @param array $packages
     * @return void
     */
    public function setPackages(array $packages): void
    {
        $this->packages = $packages;
    }

    /**
     * @return bool
     */
    public function hasPackages(): bool
    {
        return \count($this->packages) > 0;
    }

    /**
     * @param BasePackage $package
     * @return void
     */
    public function addPackage(BasePackage $package): void
    {
        $this->packages[] = $package;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $packages = [];

        /** @var BasePackage $package */
        foreach ($this->packages as $package) {
            $data = $package->toArray();
            $data['PackagingType'] = $data['Packaging'];
            unset($data['Packaging']);
            $packages[] = $data;
        }

        return $packages;
    }
}
