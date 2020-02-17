<?php

/**
 * Plugin configuration file.
 *
 * @author       Martin Welte
 * @copyright    2019 Towa
 * @license      GPL-2.0+
 */

declare(strict_types=1);

namespace Towa\GdprPlugin;

$towa_gdpr_plugin_plugin = [
    'textdomain' => 'towa-gdpr-plugin',
    'languages_dir' => 'languages',
];

$towa_gdpr_plugin_settings = [
    'submenu_pages' => [
        [
            'parent_slug' => 'options-general.php',
            'page_title' => __('Towa GDPR Settings', 'towa-gdpr-plugin'),
            'menu_title' => __('Towa GDPR', 'towa-gdpr-plugin'),
            'capability' => 'manage_options',
            'menu_slug' => 'towa-gdpr-plugin',
            'view' => TOWA_GDPR_PLUGIN_DIR . 'views/admin-page.twig',
            'redirect' => false,
            'dependencies' => [
                'styles' => [
                    [
                        'handle' => 'towa-gdpr-plugin-css',
                        'src' => TOWA_GDPR_PLUGIN_URL . 'dist/css/main.css',
                        'deps' => '',
                        'ver' => '1.0.10',
                        'media' => 'all',
                    ],
                ],
                'scripts' => [
                    [
                        'handle' => 'towa-gdpr-plugin-js',
                        'src' => TOWA_GDPR_PLUGIN_URL.'dist/js/main.js',
                        'ver' => '1.0.10',
                        'in_footer' => true,
                        'localize' => [
                            'name' => 'towaGdprContext',
							// phpcs:ignore NeutronStandard.Functions.TypeHint.NoArgumentType -- Mixed type
                            'data' => function ($context): array {
                                return [
                                    'context' => $context,
                                    'settings' => function_exists('get_fields') ? Plugin::getData() : [],
                                ];
                            },
                        ],
                    ],
                ],
                'handlers' => [
                    'scripts' => 'BrightNucleus\Dependency\ScriptHandler',
                    'styles' => 'BrightNucleus\Dependency\StyleHandler',
                ],
            ],
        ],
    ],
    'tagmanager' => [
        'dependencies' => [
            'scripts' => [
                [
                    'handle' => 'towa-gdpr-plugin-tagmanager',
                    'src' => TOWA_GDPR_PLUGIN_URL . 'dist/js/tagmanager.js',
                    'ver' => '1.0.10',
                    'in_footer' => false,
                    'localize' => [
                        'name' => 'towaTagmanager ',
						// phpcs:ignore NeutronStandard.Functions.TypeHint.NoArgumentType -- Mixed type
                        'data' => function ($context): array {
                            return [
                                'id' => get_field('tagmanager', 'option'),
                            ];
                        },
                    ],
                ],
            ],
            'handlers' => [
                'scripts' => 'BrightNucleus\Dependency\ScriptHandler',
            ],
        ],
    ],
    'settings' => [],
];

$towa_gdpr_backup_settings = [
    'types' => [
        'local' => [
            'class' => 'Towa\GdprPlugin\Backup\FtpBackup',
            'id' => 'local',
            'name' => __('Local', 'towa_gdpr_plugin')
        ]
    ]
];

return [
    'Towa' => [
        'GdprPlugin' => [
            'Plugin' => $towa_gdpr_plugin_plugin,
            'Settings' => $towa_gdpr_plugin_settings,
            'Backup' => $towa_gdpr_backup_settings
        ],
    ],
];
