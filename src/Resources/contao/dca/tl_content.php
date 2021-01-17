<?php

use Contao\DataContainer;
use Contao\CoreBundle\DataContainer\PaletteManipulator;

$GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'][] = function (DataContainer $dc): void {
  foreach ($GLOBALS['TL_DCA'][$dc->table]['palettes'] as $key => $palette) {
    if (\is_string($palette)) {
      PaletteManipulator::create()
              ->addLegend('alpdeskclass_legend', 'expert_legend', PaletteManipulator::POSITION_AFTER, true)
              ->addField('hasAlpdeskclass', 'alpdeskclass_legend', PaletteManipulator::POSITION_APPEND)
              ->applyToPalette($key, $dc->table);
    }
  }
};

$GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'][] = 'hasAlpdeskclass';
$GLOBALS['TL_DCA']['tl_content']['subpalettes']['hasAlpdeskclass'] = 'alpdeskclass';

$GLOBALS['TL_DCA']['tl_content']['fields']['hasAlpdeskclass'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_content']['hasAlpdeskclass'],
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => ['submitOnChange' => true],
    'sql' => "char(1) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_content']['fields']['alpdeskclass'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_content']['alpdeskclass'],
    'exclude' => true,
    'inputType' => 'checkbox',
    'foreignKey' => 'tl_alpdeskclasses.title',
    'eval' => [
        'multiple' => true,
        'tl_class' => 'clr',
    ],
    'sql' => "mediumtext NULL"
];