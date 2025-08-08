<?php

/**
 *  Copyright (c) 2025 Rhyme Digital, LLC.
 *
 *  @link			https://rhyme.digital
 *  @license		https://www.gnu.org/licenses/lgpl-3.0.txt LGPL
 */

declare(strict_types=1);

namespace Rhymedigital\IsotopeUpsShipping\Entity\UpsApi\Entity;

use RahulGodiyal\PhpUpsApiWrapper\Entity\Request;
use RahulGodiyal\PhpUpsApiWrapper\Entity\Shipment;

class RateRequest
{
    private Request $request;
    private Shipment $shipment;

    public function setRequest(Request $request): self
    {
        $this->request = $request;
        return $this;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function setShipment(Shipment $shipment): self
    {
        $this->shipment = $shipment;
        return $this;
    }

    public function getShipment(): Shipment
    {
        return $this->shipment;
    }

    public function toArray(): array
    {
        $rateRequest = [
            "Request" => $this->request->toArray(),
            "Shipment" => $this->shipment->toArray()
        ];

        return $rateRequest;
    }
}
