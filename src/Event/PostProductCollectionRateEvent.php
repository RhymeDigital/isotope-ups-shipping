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
use RahulGodiyal\PhpUpsApiWrapper\Ship;
use Symfony\Contracts\EventDispatcher\Event;

class PostProductCollectionRateEvent extends Event
{
    public const NAME = 'rhyme.isotope.ups_shipping.post_product_collection_rate';

    protected IsotopeOrderableCollection $collection;
    protected IsotopeShipping $shipping;
    protected Ship $ship;
    protected array $response;
    protected array $options;

    public function __construct(IsotopeOrderableCollection $collection, IsotopeShipping $shipping, Ship $ship, array $response, array $options=[])
    {
        $this->collection = $collection;
        $this->shipping = $shipping;
        $this->ship = $ship;
        $this->response = $response;
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

    public function getShip(): Ship
    {
        return $this->ship;
    }

    public function setShip(Ship $ship): void
    {
        $this->ship = $ship;
    }

    public function getResponse(): array
    {
        return $this->response;
    }

    public function setResponse(array $response): void
    {
        $this->response = $response;
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
