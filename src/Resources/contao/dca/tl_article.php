<?php

declare(strict_types=1);

use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\Backend;
use Alpdesk\AlpdeskClasses\Model\AlpdeskClassesModel;

PaletteManipulator::create()
    ->addLegend('alpdeskclass_legend', 'syndication_legend', PaletteManipulator::POSITION_BEFORE, true)
    ->addField('hasAlpdeskclass', 'alpdeskclass_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('default', 'tl_article');

$GLOBALS['TL_DCA']['tl_article']['palettes']['__selector__'][] = 'hasAlpdeskclass';
$GLOBALS['TL_DCA']['tl_article']['subpalettes']['hasAlpdeskclass'] = 'alpdeskclass';


$GLOBALS['TL_DCA']['tl_article']['fields']['hasAlpdeskclass'] = [
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => ['submitOnChange' => true],
    'sql' => "char(1) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_article']['fields']['alpdeskclass'] = [
    'exclude' => true,
    'inputType' => 'checkbox',
    'options_callback' => array('tl_article_alpdeskclasses', 'getArticleClasses'),
    'eval' => [
        'multiple' => true,
        'tl_class' => 'clr',
    ],
    'sql' => "mediumtext NULL"
];

class tl_article_alpdeskclasses extends Backend
{
    /**
     * @return array
     */
    public function getArticleClasses(): array
    {
        $data = [];

        $classObjects = AlpdeskClassesModel::findAll(['order' => 'title ASC']);
        if ($classObjects !== null) {

            foreach ($classObjects as $classObject) {

                if ((int)$classObject->classtype === 1) {
                    $data[$classObject->id] = $classObject->title;
                }

            }

        }

        return $data;
    }

}
