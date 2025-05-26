<?php

declare(strict_types=1);

/*
 * ALTRAD Login Client for Contao Open Source CMS Copyright (c) 2021-2025.
 * WebEx Machina
 *
 * @see     https://github.com/Web-Ex-Machina/altrad-contao-login-client/
 */

use Contao\CoreBundle\DataContainer\PaletteManipulator;

$GLOBALS['TL_DCA']['tl_settings']['fields']['altradLoginClientRemoteWebsite'] = [
    'inputType' => 'text',
    'eval' => [
        'rgxp' => 'url',
    ],
];

$GLOBALS['TL_DCA']['tl_settings']['fields']['altradLoginClient_appName'] = [
    'exclude' => true,
    'options_callback' => [ModuleContainer::class, 'getApps'],
    'inputType' => 'select',
];


// $GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ';{altradloginclient_legend},altradLoginClientRemoteWebsite,altradLoginClientApiKey';
PaletteManipulator::create()
    ->addField('altradLoginClientRemoteWebsite', 'altradloginclient_legend', PaletteManipulator::POSITION_APPEND)
    ->addField('altradLoginClientApiKey', 'altradloginclient_legend', PaletteManipulator::POSITION_APPEND)
    ->addField('altradLoginClient_appName', 'altradloginclient_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('default', 'tl_settings')
;
