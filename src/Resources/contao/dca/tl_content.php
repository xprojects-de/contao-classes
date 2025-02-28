<?php

declare(strict_types=1);

use Contao\DataContainer;
use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\Backend;
use Alpdesk\AlpdeskClasses\Model\AlpdeskClassesModel;

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
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => ['submitOnChange' => true],
    'sql' => "char(1) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_content']['fields']['alpdeskclass'] = [
    'exclude' => true,
    'inputType' => 'checkbox',
    'options_callback' => array('tl_ce_alpdeskclasses', 'getElementClasses'),
    'eval' => [
        'multiple' => true,
        'tl_class' => 'clr',
    ],
    'sql' => "mediumtext NULL"
];

class tl_ce_alpdeskclasses extends Backend
{
    /**
     * @return array
     */
    public function getElementClasses(): array
    {
        $data = [];

        $classObjects = AlpdeskClassesModel::findAll(['order' => 'title ASC']);

        if ($classObjects !== null) {

            foreach ($classObjects as $classObject) {

                if ((int)$classObject->classtype === 2) {
                    $data[$classObject->id] = $classObject->title;
                }

            }

        }

        return $data;

    }

}
