<?php

declare(strict_types=1);

use Alpdesk\AlpdeskClasses\Model\AlpdeskClassesModel;

$GLOBALS['TL_MODELS']['tl_alpdeskclasses'] = AlpdeskClassesModel::class;

$GLOBALS['BE_MOD']['content']['alpdeskclasses'] = [
    'tables' => ['tl_alpdeskclasses']
];

