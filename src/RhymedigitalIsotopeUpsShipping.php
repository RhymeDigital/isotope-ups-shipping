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

namespace Rhymedigital\IsotopeUpsShipping;

use Rhymedigital\IsotopeUpsShipping\DependencyInjection\Compiler\AddSessionBagsPass;
use Rhymedigital\IsotopeUpsShipping\DependencyInjection\RhymedigitalIsotopeUpsShippingExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class RhymedigitalIsotopeUpsShipping
 */
class RhymedigitalIsotopeUpsShipping extends Bundle
{
	public function getContainerExtension(): RhymedigitalIsotopeUpsShippingExtension
	{
		return new RhymedigitalIsotopeUpsShippingExtension();
	}

	/**
	 * {@inheritdoc}
	 */
	public function build(ContainerBuilder $container): void
	{
		parent::build($container);
		
		$container->addCompilerPass(new AddSessionBagsPass());
		
	}
}
