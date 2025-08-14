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
use Rhymedigital\IsotopeUpsShipping\Entity\UpsApi\Rating;
use Symfony\Contracts\EventDispatcher\Event;

class PostProductCollectionRateEvent extends Event
{
    public const NAME = 'rhyme.isotope.ups_shipping.post_product_collection_rate';

    protected IsotopeOrderableCollection $collection;
    protected IsotopeShipping $shipping;
    protected Rating $rating;
    protected array $response;
    protected array $options;

    public function __construct(IsotopeOrderableCollection $collection, IsotopeShipping $shipping, Rating $rating, array $response, array $options=[])
    {
        $this->collection = $collection;
        $this->shipping = $shipping;
        $this->rating = $rating;
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

    public function getRating(): Rating
    {
        return $this->rating;
    }

    public function setRating(Rating $rating): void
    {
        $this->rating = $rating;
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
