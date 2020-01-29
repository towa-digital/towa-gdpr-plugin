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
class AcfSettings implements AcfGroup
{

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
	public function register(string $page): void
	{
		if (function_exists('acf_add_local_field_group')) {
			\acf_add_local_field_group(
				[
					'key' => $this->name,
					'title' => __('Settings', 'towa-gdpr-plugin'),
					'fields' => $this->buildFields(),
					'location' => [
						[
							[
								'param' => 'options_page',
								'operator' => '==',
								'value' => $page,
							],
						],
					],
					'menu_order' => 4,
					'position' => 'acf_after_title',
					'style' => 'default',
					'label_placement' => 'left',
					'instruction_placement' => 'label',
					'hide_on_screen' => [],
					'active' => 1,
					'description' => '',
				]
			);
		}
	}

	/**
	 * Function build_fields returns Array of ACF Fields
	 *
	 * @return array
	 */
	public function buildFields(): array
	{
		return [
			(new Text($this->name, 'tagmanager', __('Tagmanager ID', 'towa-gdpr-plugin')))->build(
				[
					'instructions' => __('this will add the tagmanager installation script to the header of the page (be aware that it will not support the noscript iframe)', 'towa-gdpr-plugin'),
				]
			),
			(new Wysiwyg($this->name, 'cookie_wysiwyg', __('Cookie Notice general Information', 'towa-gdpr-plugin')))->build(),
			(new Text($this->name, 'accept_label', __('accept all Cookies text', 'towa-gdpr-plugin')))->build(
				[
					'default_value' => __('accept all', 'towa-gdpr-plugin'),
					'placeholder' => __('accept all', 'towa-gdpr-plugin'),
				]
			),
			(new Text($this->name, 'custom_accept_classes', __('accept all Cookies button css classes', 'towa-gdpr-plugin')))->build(
				[
					'width' => 'small',
				]
			),

			(new Text($this->name, 'save_label', __('Save Buttontext', 'towa-gdpr-plugin')))->build(
				[
					'default_value' => __('save', 'towa-gdpr-plugin'),
					'placeholder' => __('save', 'towa-gdpr-plugin'),
				]
			),
			(new Text($this->name, 'custom_save_classes', __('save button css classes', 'towa-gdpr-plugin')))->build(),

			(new Text($this->name, 'decline_label', __('Decline Buttontext', 'towa-gdpr-plugin')))->build(
				[
					'default_value' => __('decline all', 'towa-gdpr-plugin'),
				]
			),
			(new Text($this->name, 'custom_decline_classes', __('decline button css classes', 'towa-gdpr-plugin')))->build(
				[
					'width' => 'small',
				]
			),
			(new ColorPicker($this->name, 'highlight_color', __('hightlight color', 'towa-gdpr-plugin')))->build(
				[
					'instructions' => __('this functionality is made with Css variables, thus older Browsers wont support it. Default color in this case is "green"', 'towa-gdpr-plugin'),
				]
			),
			(new Number($this->name, 'cookieTime', __('Number of Days until new consent is required', 'towa-gdpr-plugin')))->build(
				[
					'default_value' => 90,
					'placeholder' => '90',
				]
			),
			(new Text($this->name, 'hash', __('current Hash', 'towa-gdpr-plugin')))->build(
				[
					'readonly' => true,
					'instructions' => __('The hash is used to verify the current version of the consent message. if this differs with a users hash, the consent notification will be shown again', 'towa-gdpr-plugin'),
				]
			),
		];
	}
}
