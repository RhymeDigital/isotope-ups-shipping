<?php

/**
 *  Copyright (c) 2025 Rhyme Digital, LLC.
 *
 *  @link			https://rhyme.digital
 *  @license		https://www.gnu.org/licenses/lgpl-3.0.txt LGPL
 */

declare(strict_types=1);

namespace Rhymedigital\IsotopeUpsShipping\Event;

use Isotope\Interfaces\IsotopeOrderableCollection;
use Isotope\Interfaces\IsotopeShipping;
use Symfony\Contracts\EventDispatcher\Event;

class ValidateProductCollectionRateEvent extends Event
{
    public const NAME = 'rhyme.isotope.ups_shipping.prevalidate_product_collection_rate';

    protected IsotopeOrderableCollection $collection;
    protected IsotopeShipping $shipping;
    protected array $options;

    public function __construct(IsotopeOrderableCollection $collection, ?IsotopeShipping $shipping=null, array $options=[])
    {
        $this->collection = $collection;
        $this->shipping = $shipping ?? $collection->getShippingMethod();
        $this->options = $options;
    }

    public function getCollection(): IsotopeOrderableCollection
    {
        return $this->collection;
    }

    public function setCollection(IsotopeOrderableCollection $collection): void
    {
        $this->collection = $collection;
    }

    public function getShipping(): IsotopeShipping
    {
        return $this->shipping;
    }

    public function setShipping(IsotopeShipping $shipping): void
    {
        $this->shipping = $shipping;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setOptions(array $options): void
    {
        $this->options = $options;
    }
}
