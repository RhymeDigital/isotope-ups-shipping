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

    $t = 'tl_iso_shipment';

    if (isset($GLOBALS['TL_DCA'][$t]))
    {
        $dca = &$GLOBALS['TL_DCA'][$t];

        /**
         * Palettes
         */
        PaletteManipulator::create()
            ->addLegend('ups_legend', 'details_legend')
            ->addField('rhyme_ups_shipment_identification_number', 'ups_legend', PaletteManipulator::POSITION_APPEND)
            ->addField('rhyme_ups_shipment_voided_at', 'ups_legend', PaletteManipulator::POSITION_APPEND)
            ->applyToPalette('default', $t)
        ;

        /**
         * Fields
         */
        $dca['fields'] = \array_merge($dca['fields'], [
            'rhyme_ups_shipment_identification_number' => [
                'exclude'                 => true,
                'inputType'               => 'text',
                'eval'                    => ['maxlength' => 32, 'doNotCopy'=>true, 'tl_class' => 'w50 clr'],
                'sql'                     => "varchar(32) NOT NULL default ''"
            ],
            'rhyme_ups_shipment_voided_at' => [
                'exclude'                 => true,
                'inputType'               => 'text',
                'eval'                    => array('rgxp'=>'datim', 'doNotCopy'=>true, 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
                'sql'                     => "varchar(10) NOT NULL default ''"
            ],
        ]);
    }

}
