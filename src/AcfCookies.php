<?php

namespace Towa\DsgvoPlugin;

use Towa\Acf\Fields\Group;
use Towa\Acf\Fields\Link;
use Towa\Acf\Fields\Repeater;
use Towa\Acf\Fields\Text;
use Towa\Acf\Fields\Textarea;

class AcfCookies implements AcfGroup
{
	private $name = 'towa_dsgvo_cookies';

	public function register(string $page)
	{
		if (function_exists('acf_add_local_field_group')) {
			\acf_add_local_field_group([
				'key' => $this->name,
				'title' => __('Cookies','towa-dsgvo-plugin'),
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
				'menu_order' => 5,
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

	public function build_fields():array
	{
		return [
			(new Group($this->name,'essential_group',__('Essential Cookies','towa-dsgvo-plugin')))->build([
				'sub_fields' => [
					(new Text($this->name, 'title', __('Groupname', 'towa-dsgvo-plugin')))->build([
						'default_value' => __('Essential Cookies','towa-dsgvo-plugin'),
						'placeholder' => __('Essential Cookies','towa-dsgvo-plugin'),
						]),
					(new Text($this->name, 'group_description', __('Group description', 'towa-dsgvo-plugin')))->build([
							'default_value' => __('These cookies are vital for the functionality of the website','towa-dsgvo-plugin'),
							'placeholder' => __('These cookies are vital for the functionality of the website','towa-dsgvo-plugin'),
					]),
					(new Repeater($this->name, 'cookies', __('Cookies', 'towa-dsgvo-plugin')))->build([
						'sub_fields' => [
							(new Text($this->name,'name', __('Name of Cookie', 'towa-dsgvo-plugin')))->build([
								'wrapper' => [
									'width' => '33%'
								]
							]),
							(new Link($this->name, 'link', __('link to Provider', 'towa-dsgvo-plugin')))->build([
								'wrapper' => [
									'width' => '33%'
								]
							]),
							(new Text($this->name, 'description', __('Description', 'towa-dsgvo-plugin')))->build([
								'wrapper' => [
									'width' => '33%'
								]
							]),
							(new Textarea($this->name, 'javascript', __('javascript', 'towa-dsgvo-plugin')))->build([
								'instructions' => __('add custom javascript that is triggered, as soon as the User accepts the cookies','towa-dsgvo-plugin')
							])
						],
						'min' => 1,
						'layout' => 'block'
					]),

				]
			]),
			(new Repeater($this->name, 'cookie_groups', __('Cookie groups','towa-dsgvo-plugin')))->build([
				'sub_fields' => [
					(new Text($this->name, 'title', __('Groupname', 'towa-dsgvo-plugin')))->build(),
					(new Text($this->name, 'group_description', __('Group description','towa-dsgvo-plugin')))->build(),
					(new Repeater($this->name, 'cookies', __('Cookies', 'towa-dsgvo-plugin')))->build([
						'sub_fields' => [
							(new Text($this->name, 'name', __('Name of Cookie', 'towa-dsgvo-plugin')))->build([
								'wrapper' => [
									'width' => '33%'
								],
								'required' => true
							]),
							(new Link($this->name, 'link', __('link to Provider', 'towa-dsgvo-plugin')))->build([
								'wrapper' => [
									'width' => '33%'
								],
							]),
							(new Textarea($this->name, 'description', __('Description', 'towa-dsgvo-plugin')))->build([
								'wrapper' => [
									'width' => '33%'
								],
								'required' => true
							]),
							(new Textarea($this->name, 'javascript', __('javascript', 'towa-dsgvo-plugin')))->build()
						],
						'layout' => 'block',
						'min' => 1
					]),

				],
				'min'=> 1,
				'layout' => 'block'
			]),
		];
	}
}
