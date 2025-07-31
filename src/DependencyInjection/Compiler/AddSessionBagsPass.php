<?php

declare(strict_types=1);

/*
 * This file is part of Isotope UPS Shipping.
 * 
 * (c) Rhyme Digital 2025 <info@rhyme.digital>
 * @license GPL-3.0-or-later
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/rhymedigital/isotope-ups-shipping
 */

namespace Rhymedigital\IsotopeUpsShipping\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @internal
 */
class AddSessionBagsPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has('session')) {
            return;
        }

        $session = $container->findDefinition('session');
        $session->addMethodCall('registerBag', [new Reference('Rhymedigital\IsotopeUpsShipping\Session\Attribute\ArrayAttributeBag')]);
    }
}
