<?php

/**
 * AcfCokies File.
 *
 * @author Martin Welte
 * @copyright 2019 Towa
 */

namespace Towa\GdprPlugin;

use Towa\Acf\Fields\Group;
use Towa\Acf\Fields\Link;
use Towa\Acf\Fields\Repeater;
use Towa\Acf\Fields\Text;
use Towa\Acf\Fields\Textarea;

/**
 * Class AcfCookies: registers Acf Group for Cookies.
 */
class AcfCookies implements AcfGroup
{
	/**
	 * Name of AcfGroup.
	 *
	 * @var string
	 */
	private $name = 'towa_gdpr_cookies';

	/**
	 * Register AcfGroup.
	 *
	 * @param string $page options page where the Acf Group should be displayed
	 */
	public function register(string $page): void
	{
		if (function_exists('acf_add_local_field_group')) {
			\acf_add_local_field_group(
				array(
					'key' => $this->name,
					'title' => __('Cookies', 'towa-gdpr-plugin'),
					'fields' => $this->build_fields(),
					'location' => array(
						array(
							array(
								'param' => 'options_page',
								'operator' => '==',
								'value' => $page,
							),
						),
					),
					'menu_order' => 5,
					'position' => 'acf_after_title',
					'style' => 'default',
					'label_placement' => 'left',
					'instruction_placement' => 'label',
					'hide_on_screen' => array(),
					'active' => 1,
					'description' => '',
				)
			);
		}
	}

	/**
	 * Function build_fields returns Array of ACF Fields.
	 *
	 * @return array
	 */
	public function build_fields(): array
	{
		return array(
			(new Group($this->name, 'essential_group', __('Essential Cookies', 'towa-gdpr-plugin')))->build(
				array(
					'sub_fields' => array(
						(new Text($this->name, 'essential_title', __('Groupname', 'towa-gdpr-plugin')))->build(
							array(
								'default_value' => __('Essential Cookies', 'towa-gdpr-plugin'),
								'placeholder' => __('Essential Cookies', 'towa-gdpr-plugin'),
							)
						),
						(new Text($this->name, 'essential_group_description', __('Group description', 'towa-gdpr-plugin')))->build(
							array(
								'default_value' => __('These cookies are vital for the functionality of the website', 'towa-gdpr-plugin'),
								'placeholder' => __('These cookies are vital for the functionality of the website', 'towa-gdpr-plugin'),
							)
						),
						(new Repeater($this->name, 'essential_cookies', __('Cookies', 'towa-gdpr-plugin')))->build(
							array(
								'sub_fields' => array(
									(new Text($this->name, 'name', __('Name of Cookie', 'towa-gdpr-plugin')))->build(
										array(
											'wrapper' => array(
												'width' => '33%',
											),
										)
									),
									(new Link($this->name, 'link', __('link to Provider', 'towa-gdpr-plugin')))->build(
										array(
											'wrapper' => array(
												'width' => '33%',
											),
										)
									),
									(new Text($this->name, 'description', __('Description', 'towa-gdpr-plugin')))->build(
										array(
											'wrapper' => array(
												'width' => '33%',
											),
										)
									),
									(new Textarea($this->name, 'javascript', __('javascript', 'towa-gdpr-plugin')))->build(
										array(
											'new_lines' => '',
											'instructions' => __('add custom javascript that is triggered, as soon as the User accepts the cookies', 'towa-gdpr-plugin'),
										)
									),
								),
								'min' => 1,
								'layout' => 'block',
							)
						),
					),
				)
			),
			(new Repeater($this->name, 'cookie_groups', __('Cookie groups', 'towa-gdpr-plugin')))->build(
				array(
					'sub_fields' => array(
						(new Text($this->name, 'title', __('Groupname', 'towa-gdpr-plugin')))->build(),
						(new Text($this->name, 'group_description', __('Group description', 'towa-gdpr-plugin')))->build(),
						(new Repeater($this->name, 'cookies', __('Cookies', 'towa-gdpr-plugin')))->build(
							array(
								'sub_fields' => array(
									(new Text($this->name, 'name', __('Name of Cookie', 'towa-gdpr-plugin')))->build(
										array(
											'wrapper' => array(
												'width' => '33%',
											),
											'required' => true,
										)
									),
									(new Link($this->name, 'link', __('link to Provider', 'towa-gdpr-plugin')))->build(
										array(
											'wrapper' => array(
												'width' => '33%',
											),
										)
									),
									(new Textarea($this->name, 'description', __('Description', 'towa-gdpr-plugin')))->build(
										array(
											'wrapper' => array(
												'width' => '33%',
											),
											'required' => true,
										)
									),
									(new Textarea($this->name, 'javascript', __('javascript', 'towa-gdpr-plugin')))->build(
										array(
											'new_lines' => '',
										)
									),
								),
								'layout' => 'block',
								'min' => 1,
							)
						),
					),
					'min' => 1,
					'layout' => 'block',
				)
			),
		);
	}
}
