<?php
/**
 * Acf Settings File
 *
 * @package Towa\DsgvoPlugin
 * @author  Martin Welte
 * @copyright Towa 2019
 */

namespace Towa\DsgvoPlugin;

use Towa\Acf\Fields\ColorPicker;
use Towa\Acf\Fields\Text;
use Towa\Acf\Fields\Number;
use Towa\Acf\Fields\Wysiwyg;

/**
 * Class AcfSettings
 *
 * @package Towa\DsgvoPlugin
 */
class AcfSettings implements AcfGroup {

	/**
	 * Name of AcfGroup
	 *
	 * @var string
	 */
	public $name = 'towa_dsgvo_settings';

	/**
	 * Register AcfGroup
	 *
	 * @param string $page options page where the Acf Group should be displayed.
	 */
	public function register( string $page ) {
		if ( function_exists( 'acf_add_local_field_group' ) ) {
			\acf_add_local_field_group(
				array(
					'key'                   => $this->name,
					'title'                 => __( 'Settings', 'towa-dsgvo-plugin' ),
					'fields'                => $this->build_fields(),
					'location'              => array(
						array(
							array(
								'param'    => 'options_page',
								'operator' => '==',
								'value'    => $page,
							),
						),
					),
					'menu_order'            => 4,
					'position'              => 'acf_after_title',
					'style'                 => 'default',
					'label_placement'       => 'left',
					'instruction_placement' => 'label',
					'hide_on_screen'        => array(),
					'active'                => 1,
					'description'           => '',
				)
			);
		}
	}

	/**
	 * Function build_fields returns Array of ACF Fields
	 *
	 * @return array
	 */
	public function build_fields() : array {
		return array(
			( new Wysiwyg( $this->name, 'cookie_wysiwyg', __( 'Cookie Notice general Information', 'towa-dsgvo-plugin' ) ) )->build(),
			( new Text( $this->name, 'accept_label', __( 'accept all Cookies text', 'towa-dsgvo-plugin' ) ) )->build(
				array(
					'default_value' => __( 'accept all', 'towa-dsgvo-plugin' ),
					'placeholder'   => __( 'accept all', 'towa-dsgvo-plugin' ),
				)
			),
			( new Text( $this->name, 'custom_accept_classes', __( 'accept all Cookies button css classes', 'towa-dsgvo-plugin' ) ) )->build(
				array(
					'width' => 'small',
				)
			),

			( new Text( $this->name, 'save_label', __( 'Save Buttontext', 'towa-dsgvo-plugin' ) ) )->build(
				array(
					'default_value' => __( 'save', 'towa-dsgvo-plugin' ),
					'placeholder'   => __( 'save', 'towa-dsgvo-plugin' ),
				)
			),
			( new Text( $this->name, 'custom_save_classes', __( 'save button css classes', 'towa-dsgvo-plugin' ) ) )->build(),

			( new Text( $this->name, 'decline_label', __( 'Decline Buttontext', 'towa-dsgvo-plugin' ) ) )->build(
				array(
					'default_value' => __( 'decline all', 'towa-dsgvo-plugin' ),
				)
			),
			( new Text( $this->name, 'custom_decline_classes', __( 'decline button css classes', 'towa-dsgvo-plugin' ) ) )->build(
				array(
					'width' => 'small',
				)
			),
			( new ColorPicker( $this->name, 'highlight_color', __( 'hightlight color', 'towa-dsgvo-plugin' ) ) )->build(
				array(
					'instructions' => __( 'this functionality is made with Css variables, thus older Browsers wont support it. Default color in this case is "green"', 'towa-dsgvo-plugin' ),
				)
			),
			( new Number( $this->name, 'cookieTime', __( 'Number of Days until new consent is required', 'towa-dsgvo-plugin' ) ) )->build(
				array(
					'default_value' => 90,
					'placeholder'   => '90',
				)
			),
		);
	}
}
