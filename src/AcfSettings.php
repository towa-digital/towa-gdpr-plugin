<?php

namespace Towa\DsgvoPlugin;

use Towa\Acf\Fields\ColorPicker;
use Towa\Acf\Fields\Text;
use Towa\Acf\Fields\Textarea;
use Towa\Acf\Fields\Number;
use Towa\Acf\Fields\Wysiwyg;

class AcfSettings implements AcfGroup
{
	public $name = 'towa_dsgvo_settings';

	public function register(string $page)
	{
		if (function_exists('acf_add_local_field_group')) {
			\acf_add_local_field_group([
				'key' => $this->name,
				'title' => __('Settings','towa-dsgvo-plugin'),
				'fields' => $this->build_fields(),
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
			]);
		}
	}

	public function build_fields()
	{
		return [
			(new Wysiwyg($this->name,'cookie_wysiwyg',__('Cookie Notice general Information','towa-dsgvo-plugin')))->build(),
			(new Text($this->name,'accept_label',__('accept all Cookies text','towa-dsgvo-plugin')))->build([
				'default_value' => __('accept all','towa-dsgvo-plugin'),
				'placeholder' => __('accept all','towa-dsgvo-plugin'),
			]),
			(new Text($this->name,'custom_accept_classes',__('accept all Cookies button css classes','towa-dsgvo-plugin')))->build([
				'width' => 'small'
			]),

			(new Text($this->name,'save_label',__('Save Buttontext','towa-dsgvo-plugin')))->build([
				'default_value' => __('save','towa-dsgvo-plugin'),
				'placeholder' => __('save','towa-dsgvo-plugin')
			]),
			(new Text($this->name,'custom_save_classes',__('save button css classes','towa-dsgvo-plugin')))->build(),

			(new Text($this->name,'decline_label',__('Decline Buttontext','towa-dsgvo-plugin')))->build([
				'default_value' => __('decline all','towa-dsgvo-plugin')
			]),
			(new Text($this->name,'custom_decline_classes',__('decline button css classes','towa-dsgvo-plugin')))->build([
				'width' => 'small',
			]),
			(new ColorPicker($this->name,'highlight_color', __('hightlight color','towa-dsgvo-plugin')))->build([
				'instructions' => __('this functionality is made with Css variables, thus older Browsers wont support it. Default color in this case is "green"')
			]),
			(new Number($this->name,'cookieTime',__('Number of Days until new consent is required')))->build([
				'default_value' => 90,
				'placeholder' => '90'
			])
		];
	}
}
