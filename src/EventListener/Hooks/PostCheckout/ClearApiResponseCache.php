<?php

/**
 *  Copyright (c) 2025 Rhyme Digital, LLC.
 *
 *  @link			https://rhyme.digital
 *  @license		https://www.gnu.org/licenses/lgpl-3.0.txt LGPL
 */

declare(strict_types=1);

namespace Rhymedigital\IsotopeUpsShipping\EventListener\Hooks\PostCheckout;

use Isotope\Model\ProductCollection\Order;
use Isotope\ServiceAnnotation\IsotopeHook;
use Rhymedigital\IsotopeUpsShipping\Session\ApiResponseCache;

/**
 * @IsotopeHook("postCheckout")
 */
class ClearApiResponseCache
{
    /**
     * @var ApiResponseCache
     */
    private ApiResponseCache $apiResponseCache;

    /**
     * @param ApiResponseCache $apiResponseCache
     */
    public function __construct(ApiResponseCache $apiResponseCache) {
        $this->apiResponseCache = $apiResponseCache;
    }

    /**
     * @param Order $order
     * @param array $tokens
     * @return void
     */
    public function __invoke(Order $order, array $tokens): void
    {
        $this->apiResponseCache->clear();
    }
}
