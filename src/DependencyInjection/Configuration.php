<?php

declare(strict_types=1);

/*
 * This file is part of Isotope UPS Shipping.
 * 
 * (c) Rhyme Digital 2025 <info@rhyme.digital>
 * @license GPL-3.0-or-later
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/rhymedigital/contao-rhyme-isotope-ups-shipping
 */

namespace Rhymedigital\ContaoRhymeIsotopeUpsShipping\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    const ROOT_KEY = 'rhymedigital_contao_rhyme_isotope_ups_shipping';

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder(self::ROOT_KEY);

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('foo')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('bar')
                            ->cannotBeEmpty()
                            ->defaultValue('***')
                        ->end()
                    ->end()
                ->end() // end foo
            ->end()
        ;

        return $treeBuilder;
    }
}
