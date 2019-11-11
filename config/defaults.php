<?php
/**
 * Plugin configuration file
 *
 * @package      Towa\DsgvoPlugin
 * @author       Martin Welte
 * @copyright    2019 Towa
 * @license      GPL-2.0+
 */

declare( strict_types = 1 );

namespace Towa\DsgvoPlugin;

$towa_dsgvo_plugin_plugin = [
	'textdomain'    => 'towa-dsgvo-plugin',
	'languages_dir' => 'languages',
];

$towa_dsgvo_plugin_settings = [
	'submenu_pages' => [
		[
			'parent_slug'  => 'options-general.php',
			'page_title'   => __( 'Towa DSGVO Settings', 'towa-dsgvo-plugin' ),
			'menu_title'   => __( 'Towa DSGVO', 'towa-dsgvo-plugin' ),
			'capability'   => 'manage_options',
			'menu_slug'    => 'towa-dsgvo-plugin',
			'view'         => TOWA_DSGVO_PLUGIN_DIR . 'views/admin-page.twig',
			'redirect'     => false,
			'dependencies' => [
				'styles'   => [
					[
						'handle'    => 'towa-dsgvo-plugin-css',
						'src'       =>  TOWA_DSGVO_PLUGIN_URL . 'dist/css/main.css',
						'deps'      =>  '',
						'ver'       =>  '1.0.0',
						'media'     =>  'all'
					]
				],
				'scripts'  => [
					[
						'handle'    => 'towa-dsgvo-plugin-js',
						'src'       => TOWA_DSGVO_PLUGIN_URL . 'dist/js/main.js',
						'ver'       => '1.0.0',
						'in_footer' => true,
						// phpcs:ignore NeutronStandard.Functions.TypeHint.NoArgumentType -- Mixed type
						'is_needed' => function ( $context ): bool {
							if ( $context ) {
								return false;
							}

							return true;
						},
						'localize'  => [
							'name' => 'pluginSlugI18n',
							// phpcs:ignore NeutronStandard.Functions.TypeHint.NoArgumentType -- Mixed type
							'data' => function ( $context ): array {
								return [
									'test_localize_data' => 'test_localize_value',
									'context'            => $context,
								];
							},
						],
					],
				],
				'handlers' => [
					'scripts' => 'BrightNucleus\Dependency\ScriptHandler',
					'styles'  => 'BrightNucleus\Dependency\StyleHandler',
				],
			],
		],
	],
	'settings'      => [
		'setting1' => [
			'option_group'      => 'towa-dsgvo-plugin',
			'sanitize_callback' => null,
			'sections'          => [
				'section1' => [
					'title'  => __( 'My Section Title', 'towa-dsgvo-plugin' ),
					'view'   => TOWA_DSGVO_PLUGIN_DIR . 'views/section1.php',
					'fields' => [
						'field1' => [
							'title' => __( 'My Field Title', 'towa-dsgvo-plugin' ),
							'view'  => TOWA_DSGVO_PLUGIN_DIR . 'views/field1.php',
						],
					],
				],
			],
		],
	],
];

return [
	'Towa' => [
		'DsgvoPlugin' => [
			'Plugin'   => $towa_dsgvo_plugin_plugin,
			'Settings' => $towa_dsgvo_plugin_settings,
		],
	],
];
