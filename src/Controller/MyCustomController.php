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

namespace Rhymedigital\IsotopeUpsShipping\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment as TwigEnvironment;

/**
 * @Route("/my_custom", name="rhymedigital_isotope_ups_shipping_my_custom", defaults={"_scope" = "frontend", "_token_check" = true})
 */
class MyCustomController extends AbstractController
{

    private TwigEnvironment $twig;

    public function __construct(TwigEnvironment $twig)
    {
        $this->twig = $twig;
    }

    public function __invoke()
    {
        $animals = [
            [
                'species' => 'dogs',
                'color'   => 'white'
            ],
            [
                'species' => 'birds',
                'color'   => 'black'
            ], [
                'species' => 'cats',
                'color'   => 'pink'
            ], [
                'species' => 'cows',
                'color'   => 'yellow'
            ],
        ];

        return new Response($this->twig->render(
            '@RhymedigitalIsotopeUpsShipping/MyCustom/my_custom.html.twig',
            [
                'animals' => $animals,
            ]
        ));
    }
}
