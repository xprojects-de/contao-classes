<?php

use Contao\DataContainer;
use Contao\DC_Table;

$GLOBALS['TL_DCA']['tl_alpdeskclasses'] = [
    'config' => [
        'dataContainer' => DC_Table::class,
        'switchToEdit' => true,
        'enableVersioning' => true,
        'sql' => [
            'keys' => [
                'id' => 'primary'
            ]
        ]
    ],
    'list' => [
        'sorting' => [
            'mode' => DataContainer::MODE_SORTED,
            'fields' => ['title ASC'],
            'flag' => DataContainer::SORT_INITIAL_LETTER_ASC,
            'panelLayout' => 'filter,search,limit'
        ],
        'label' => [
            'fields' => ['title', 'classvalue', 'classtype'],
            'showColumns' => true,
        ],
        'global_operations' => [
            'all' => [
                'label' => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href' => 'act=select',
                'class' => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"'
            ]
        ],
        'operations' => [
            'edit' => [
                'label' => &$GLOBALS['TL_LANG']['tl_alpdeskclasses']['edit'],
                'href' => 'act=edit',
                'icon' => 'edit.gif',
            ],
            'copy' => [
                'label' => &$GLOBALS['TL_LANG']['tl_alpdeskclasses']['copy'],
                'href' => 'act=copy',
                'icon' => 'copy.gif',
            ],
            'delete' => [
                'label' => &$GLOBALS['TL_LANG']['tl_alpdeskclasses']['delete'],
                'href' => 'act=delete',
                'icon' => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\'' . ($GLOBALS['TL_LANG']['MSC']['deleteConfirm'] ?? null) . '\'))return false;Backend.getScrollOffset()"',
            ],
        ]
    ],
    'palettes' => [
        'default' => 'title,classvalue,classtype'
    ],
    'fields' => [
        'id' => [
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ],
        'tstamp' => [
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ],
        'title' => [
            'label' => &$GLOBALS['TL_LANG']['tl_alpdeskclasses']['title'],
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'tl_class' => 'w50', 'maxlength' => 250],
            'sql' => "varchar(250) NOT NULL default ''"
        ],
        'classvalue' => [
            'label' => &$GLOBALS['TL_LANG']['tl_alpdeskclasses']['classvalue'],
            'exclude' => true,
            'search' => true,
            'sorting' => true,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'tl_class' => 'w50', 'maxlength' => 250],
            'sql' => "varchar(250) NOT NULL default ''"
        ],
        'classtype' => [
            'label' => &$GLOBALS['TL_LANG']['tl_alpdeskclasses']['classtype'],
            'exclude' => true,
            'search' => true,
            'inputType' => 'select',
            'options' => [1, 2],
            'reference' => &$GLOBALS['TL_LANG']['tl_alpdeskclasses']['classtype_options'],
            'eval' => ['mandatory' => true, 'tl_class' => 'w50', 'maxlength' => 250],
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ]
    ]
];
