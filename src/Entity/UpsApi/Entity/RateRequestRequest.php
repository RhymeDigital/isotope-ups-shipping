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

class RateRequestRequest
{
    private string $customerContext;

    public function setCustomerContext(string $customerContext): self
    {
        $this->customerContext = $customerContext;
        return $this;
    }

    public function getCustomerContext(): string
    {
        return $this->customerContext;
    }

    public function toArray(): array
    {
        return [
            'TransactionReference' => [
                'CustomerContext'       => $this->customerContext,
            ],
        ];
    }
}
