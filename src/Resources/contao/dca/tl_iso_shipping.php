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
    use Isotope\Model\Shipping;

    $t = Shipping::getTable();
    $dca = &$GLOBALS['TL_DCA'][$t];


    /**
     * Palettes
     */
    $dca['palettes']['rhyme_ups'] = $dca['palettes']['flat'];

    PaletteManipulator::create()
        ->removeField('flatCalculation', 'price_legend')
        ->addLegend('ups_legend', 'title_legend')
        ->addField('rhyme_ups_client_id', 'ups_legend', PaletteManipulator::POSITION_APPEND)
        ->addField('rhyme_ups_client_secret', 'ups_legend', PaletteManipulator::POSITION_APPEND)
        ->addField('rhyme_ups_account_number', 'ups_legend', PaletteManipulator::POSITION_APPEND)
        ->addField('rhyme_ups_enabled_service', 'ups_legend', PaletteManipulator::POSITION_APPEND)
        ->addField('rhyme_ups_mode', 'ups_legend', PaletteManipulator::POSITION_APPEND)
        ->addField('rhyme_ups_insure_packages', 'ups_legend', PaletteManipulator::POSITION_APPEND)
        ->applyToPalette('rhyme_ups', $t)
    ;

    /**
     * Fields
     */
    $dca['fields'] = \array_merge($dca['fields'], [
        'rhyme_ups_client_id' => [
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50 clr'],
            'sql'                     => "varchar(255) NOT NULL default ''"
        ],
        'rhyme_ups_client_secret' => [
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => ['mandatory' => true, 'maxlength' => 255, 'hideInput' => true, 'tl_class' => 'w50'],
            'sql'                     => "varchar(255) NOT NULL default ''"
        ],
        'rhyme_ups_account_number' => [
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => ['mandatory'=>true, 'maxlength' => 6, 'tl_class' => 'w50 clr'],
            'sql'                     => "varchar(6) NOT NULL default ''"
        ],
        'rhyme_ups_enabled_service' => [
            'exclude'                 => true,
            'inputType'               => 'select',
            'options'                 => $GLOBALS['TL_LANG']['MSC']['rhyme_ups_services'],
            'eval'                    => ['mandatory' => true, 'tl_class' => 'w50'],
            'sql'                     => "varchar(255) NOT NULL default ''"
        ],
        'rhyme_ups_mode' => [
            'exclude'                 => true,
            'default'                 => 'DEV',
            'inputType'               => 'select',
            'options'                 => ['DEV', 'PROD'],
            'eval'                    => ['doNotCopy' => true, 'tl_class' => 'w50 clr'],
            'reference'               => &$GLOBALS['TL_LANG']['tl_iso_shipping']['rhyme_ups_modes'],
            'sql'                     => "varchar(8) NOT NULL default ''"
        ],
    ]);

}
