<?php

/**
 *  Copyright (c) 2025 Rhyme Digital, LLC.
 *
 *  @link			https://rhyme.digital
 *  @license		https://www.gnu.org/licenses/lgpl-3.0.txt LGPL
 */

namespace Rhymedigital\IsotopeUpsShipping\Entity\UpsApi;

use RahulGodiyal\PhpUpsApiWrapper\VoidShipment as VoidShipmentBase;

class VoidShipment extends VoidShipmentBase
{
    public function voidShipment(string $client_id, string $client_secret): array
    {
        $response = parent::voidShipment($client_id, $client_secret);

        // Workaround for the SDK checking $res->VoidShipmentResponse->Status instead of $res->VoidShipmentResponse->ResponseStatus
        if ($response['status'] === 'fail' && $response['msg'] === 'Invalid Request.') {
            $response = [
                'status' => 'success',
                'data' => [],
            ];
        }

        return $response;
    }
}
