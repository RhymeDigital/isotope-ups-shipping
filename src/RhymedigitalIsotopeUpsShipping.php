<?php

/**
 *  Copyright (c) 2025 Rhyme Digital, LLC.
 *
 *  @link			https://rhyme.digital
 *  @license		https://www.gnu.org/licenses/lgpl-3.0.txt LGPL
 */

declare(strict_types=1);

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
