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

$towa_dsgvo_plugin_plugin = array(
	'textdomain'    => 'towa-dsgvo-plugin',
	'languages_dir' => 'languages',
);

$towa_dsgvo_plugin_settings = array(
	'submenu_pages' => array(
		array(
			'parent_slug'  => 'options-general.php',
			'page_title'   => __( 'Towa DSGVO Settings', 'towa-dsgvo-plugin' ),
			'menu_title'   => __( 'Towa DSGVO', 'towa-dsgvo-plugin' ),
			'capability'   => 'manage_options',
			'menu_slug'    => 'towa-dsgvo-plugin',
			'view'         => TOWA_DSGVO_PLUGIN_DIR . 'views/admin-page.twig',
			'redirect'     => false,
			'dependencies' => array(
				'styles'   => array(
					array(
						'handle' => 'towa-dsgvo-plugin-css',
						'src'    => TOWA_DSGVO_PLUGIN_URL . 'dist/css/main.css',
						'deps'   => '',
						'ver'    => '1.0.0',
						'media'  => 'all',
					),
				),
				'scripts'  => array(
					array(
						'handle'    => 'towa-dsgvo-plugin-js',
						'src'       => TOWA_DSGVO_PLUGIN_URL . 'dist/js/main.js',
						'ver'       => '1.0.0',
						'in_footer' => true,
						'localize'  => array(
							'name' => 'towaDsgvoContext',
							// phpcs:ignore NeutronStandard.Functions.TypeHint.NoArgumentType -- Mixed type
							'data' => function ( $context ): array {
								return array(
									'context'  => $context,
									'settings' => function_exists( 'get_fields' ) ? get_fields( 'options' ) : array(),
								);
							},
						),
					),
				),
				'handlers' => array(
					'scripts' => 'BrightNucleus\Dependency\ScriptHandler',
					'styles'  => 'BrightNucleus\Dependency\StyleHandler',
				),
			),
		),
	),
	'settings'      => array(),
);

return array(
	'Towa' => array(
		'DsgvoPlugin' => array(
			'Plugin'   => $towa_dsgvo_plugin_plugin,
			'Settings' => $towa_dsgvo_plugin_settings,
		),
	),
);
