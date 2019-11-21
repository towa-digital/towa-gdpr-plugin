<?php
/**
 * AcfCokies File
 *
 * @package Towa\DsgvoPlugin
 * @author Martin Welte
 * @copyright 2019 Towa
 */

namespace Towa\DsgvoPlugin;

use Towa\Acf\Fields\Group;
use Towa\Acf\Fields\Link;
use Towa\Acf\Fields\Repeater;
use Towa\Acf\Fields\Text;
use Towa\Acf\Fields\Textarea;

/**
 * Class AcfCookies: registers Acf Group for Cookies
 *
 * @package Towa\DsgvoPlugin
 */
class AcfCookies implements AcfGroup {

	/**
	 * Name of AcfGroup
	 *
	 * @var string
	 */
	private $name = 'towa_dsgvo_cookies';

	/**
	 * Register AcfGroup
	 *
	 * @param string $page options page where the Acf Group should be displayed.
	 */
	public function register( string $page ): void {
		if ( function_exists( 'acf_add_local_field_group' ) ) {
			\acf_add_local_field_group(
				array(
					'key'                   => $this->name,
					'title'                 => __( 'Cookies', 'towa-dsgvo-plugin' ),
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
					'menu_order'            => 5,
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
	public function build_fields():array {
		return array(
			( new Group( $this->name, 'essential_group', __( 'Essential Cookies', 'towa-dsgvo-plugin' ) ) )->build(
				array(
					'sub_fields' => array(
						( new Text( $this->name, 'title', __( 'Groupname', 'towa-dsgvo-plugin' ) ) )->build(
							array(
								'default_value' => __( 'Essential Cookies', 'towa-dsgvo-plugin' ),
								'placeholder'   => __( 'Essential Cookies', 'towa-dsgvo-plugin' ),
							)
						),
						( new Text( $this->name, 'group_description', __( 'Group description', 'towa-dsgvo-plugin' ) ) )->build(
							array(
								'default_value' => __( 'These cookies are vital for the functionality of the website', 'towa-dsgvo-plugin' ),
								'placeholder'   => __( 'These cookies are vital for the functionality of the website', 'towa-dsgvo-plugin' ),
							)
						),
						( new Repeater( $this->name, 'cookies', __( 'Cookies', 'towa-dsgvo-plugin' ) ) )->build(
							array(
								'sub_fields' => array(
									( new Text( $this->name, 'name', __( 'Name of Cookie', 'towa-dsgvo-plugin' ) ) )->build(
										array(
											'wrapper' => array(
												'width' => '33%',
											),
										)
									),
									( new Link( $this->name, 'link', __( 'link to Provider', 'towa-dsgvo-plugin' ) ) )->build(
										array(
											'wrapper' => array(
												'width' => '33%',
											),
										)
									),
									( new Text( $this->name, 'description', __( 'Description', 'towa-dsgvo-plugin' ) ) )->build(
										array(
											'wrapper' => array(
												'width' => '33%',
											),
										)
									),
									( new Textarea( $this->name, 'javascript', __( 'javascript', 'towa-dsgvo-plugin' ) ) )->build(
										array(
											'instructions' => __( 'add custom javascript that is triggered, as soon as the User accepts the cookies', 'towa-dsgvo-plugin' ),
										)
									),
								),
								'min'        => 1,
								'layout'     => 'block',
							)
						),

					),
				)
			),
			( new Repeater( $this->name, 'cookie_groups', __( 'Cookie groups', 'towa-dsgvo-plugin' ) ) )->build(
				array(
					'sub_fields' => array(
						( new Text( $this->name, 'title', __( 'Groupname', 'towa-dsgvo-plugin' ) ) )->build(),
						( new Text( $this->name, 'group_description', __( 'Group description', 'towa-dsgvo-plugin' ) ) )->build(),
						( new Repeater( $this->name, 'cookies', __( 'Cookies', 'towa-dsgvo-plugin' ) ) )->build(
							array(
								'sub_fields' => array(
									( new Text( $this->name, 'name', __( 'Name of Cookie', 'towa-dsgvo-plugin' ) ) )->build(
										array(
											'wrapper'  => array(
												'width' => '33%',
											),
											'required' => true,
										)
									),
									( new Link( $this->name, 'link', __( 'link to Provider', 'towa-dsgvo-plugin' ) ) )->build(
										array(
											'wrapper' => array(
												'width' => '33%',
											),
										)
									),
									( new Textarea( $this->name, 'description', __( 'Description', 'towa-dsgvo-plugin' ) ) )->build(
										array(
											'wrapper'  => array(
												'width' => '33%',
											),
											'required' => true,
										)
									),
									( new Textarea( $this->name, 'javascript', __( 'javascript', 'towa-dsgvo-plugin' ) ) )->build(),
								),
								'layout'     => 'block',
								'min'        => 1,
							)
						),

					),
					'min'        => 1,
					'layout'     => 'block',
				)
			),
		);
	}
}
