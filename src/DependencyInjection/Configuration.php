<?php

/**
 *  Copyright (c) 2025 Rhyme Digital, LLC.
 *
 *  @link			https://rhyme.digital
 *  @license		https://www.gnu.org/licenses/lgpl-3.0.txt LGPL
 */

declare(strict_types=1);

namespace Rhymedigital\IsotopeUpsShipping\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    const ROOT_KEY = 'rhymedigital_isotope_ups_shipping';

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
