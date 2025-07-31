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

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Rhymedigital\ContaoRhymeIsotopeUpsShipping\DependencyInjection\Configuration;

/**
 * Class RhymedigitalContaoRhymeIsotopeUpsShippingExtension
 */
class RhymedigitalContaoRhymeIsotopeUpsShippingExtension extends Extension
{

    /**
     * {@inheritdoc}
     */
    public function getAlias(): string
    {
        return Configuration::ROOT_KEY;
    }

    /**
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {

        $configuration = new Configuration();

        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );

        $loader->load('parameters.yml');
        $loader->load('services.yml');
        $loader->load('listener.yml');


        $rootKey = $this->getAlias();

        $container->setParameter($rootKey.'.foo.bar', $config['foo']['bar']);
    }
}
