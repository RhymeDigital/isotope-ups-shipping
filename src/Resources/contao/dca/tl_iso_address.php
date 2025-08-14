<?php

/**
 *  Copyright (c) 2025 Rhyme Digital, LLC.
 *
 *  @link			https://rhyme.digital
 *  @license		https://www.gnu.org/licenses/lgpl-3.0.txt LGPL
 */

declare(strict_types=1);

namespace {

    use Contao\CoreBundle\DataContainer\PaletteManipulator;
    use Isotope\Model\Address;

    $t = Address::getTable();
    $dca = &$GLOBALS['TL_DCA'][$t];

    /**
     * Palettes
     */
    PaletteManipulator::create()
        ->addField('address_classification', 'country', PaletteManipulator::POSITION_AFTER)
        ->applyToPalette('default', $t)
    ;


    /**
     * Fields
     */
    $dca['fields']['address_classification'] = array
    (
        'exclude'				=> true,
        'filter'				=> true,
        'sorting'				=> true,
        'default'				=> 'residential',
        'inputType'				=> 'select',
        'options'				=> array('residential', 'business'),
        'reference'				=> &$GLOBALS['TL_LANG']['tl_iso_address']['address_classifications'],
        'eval'					=> array('mandatory'=>true, 'feEditable'=>true, 'feGroup'=>'address', 'tl_class'=>'w50'),
        'sql'                   => "varchar(64) NOT NULL default ''"
    );
}
