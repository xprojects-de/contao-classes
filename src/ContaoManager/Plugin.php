<?php

declare(strict_types=1);

namespace Alpdesk\AlpdeskClasses\ContaoManager;

use Alpdesk\AlpdeskClasses\AlpdeskClassesBundle;
use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;

class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser): array
    {
        return [BundleConfig::create(AlpdeskClassesBundle::class)->setLoadAfter([ContaoCoreBundle::class])];
    }

}
