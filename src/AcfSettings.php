<?php
/**
 * Acf Settings File
 *
 * @package Towa\GdprPlugin
 * @author  Martin Welte
 * @copyright Towa 2019
 */

namespace Towa\GdprPlugin;

use Towa\Acf\Fields\ColorPicker;
use Towa\Acf\Fields\Text;
use Towa\Acf\Fields\Number;
use Towa\Acf\Fields\Wysiwyg;

/**
 * Class AcfSettings
 *
 * @package Towa\GdprPlugin
 */
class AcfSettings implements AcfGroup {

	/**
	 * Name of AcfGroup
	 *
	 * @var string
	 */
	public $name = 'towa_gdpr_settings';

	/**
	 * Register AcfGroup
	 *
	 * @param string $page options page where the Acf Group should be displayed.
	 */
	public function register( string $page ) : void {
		if ( function_exists( 'acf_add_local_field_group' ) ) {
			\acf_add_local_field_group(
				array(
					'key'                   => $this->name,
					'title'                 => __( 'Settings', 'towa-gdpr-plugin' ),
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
			( new Wysiwyg( $this->name, 'cookie_wysiwyg', __( 'Cookie Notice general Information', 'towa-gdpr-plugin' ) ) )->build(),
			( new Text( $this->name, 'accept_label', __( 'accept all Cookies text', 'towa-gdpr-plugin' ) ) )->build(
				array(
					'default_value' => __( 'accept all', 'towa-gdpr-plugin' ),
					'placeholder'   => __( 'accept all', 'towa-gdpr-plugin' ),
				)
			),
			( new Text( $this->name, 'custom_accept_classes', __( 'accept all Cookies button css classes', 'towa-gdpr-plugin' ) ) )->build(
				array(
					'width' => 'small',
				)
			),

			( new Text( $this->name, 'save_label', __( 'Save Buttontext', 'towa-gdpr-plugin' ) ) )->build(
				array(
					'default_value' => __( 'save', 'towa-gdpr-plugin' ),
					'placeholder'   => __( 'save', 'towa-gdpr-plugin' ),
				)
			),
			( new Text( $this->name, 'custom_save_classes', __( 'save button css classes', 'towa-gdpr-plugin' ) ) )->build(),

			( new Text( $this->name, 'decline_label', __( 'Decline Buttontext', 'towa-gdpr-plugin' ) ) )->build(
				array(
					'default_value' => __( 'decline all', 'towa-gdpr-plugin' ),
				)
			),
			( new Text( $this->name, 'custom_decline_classes', __( 'decline button css classes', 'towa-gdpr-plugin' ) ) )->build(
				array(
					'width' => 'small',
				)
			),
			( new ColorPicker( $this->name, 'highlight_color', __( 'hightlight color', 'towa-gdpr-plugin' ) ) )->build(
				array(
					'instructions' => __( 'this functionality is made with Css variables, thus older Browsers wont support it. Default color in this case is "green"', 'towa-gdpr-plugin' ),
				)
			),
			( new Number( $this->name, 'cookieTime', __( 'Number of Days until new consent is required', 'towa-gdpr-plugin' ) ) )->build(
				array(
					'default_value' => 90,
					'placeholder'   => '90',
				)
			),
		);
	}
}
