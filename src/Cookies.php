<?php

namespace Towa\DsgvoPlugin;

use Towa\Acf\Fields\Link;
use Towa\Acf\Fields\Repeater;
use Towa\Acf\Fields\Text;
use Towa\Acf\Fields\Textarea;

class Cookies implements AcfGroup
{
    private $name = 'towa_dsgvo_cookies';

    public function register(string $page)
    {
		if(function_exists('acf_add_local_field_group')){
			\acf_add_local_field_group([
				'key' => $this->name,
				'title' => 'Dsgvo Cookies',
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
            (new Repeater($this->name,'cookie_groups',__('Cookie groups')))->build([
                'sub_fields' => [
                    (new Text($this->name,'title', __('Groupname','towa-dsgvo-plugin')))->build(),
                    (new Repeater($this->name,'cookies',__('Cookies','towa-dsgvo-plugin')))->build([
                        'sub_fields' => [
                            (new Link($this->name,'link',__('link to Provider')))->build(),
                            (new Textarea($this->name,'description',__('Description','towa-dsgvo-plugin')))->build(),
                            (new Textarea($this->name,'javascript',__('javascript','towa-dsgvo-plugin')))->build()
                        ],
                        'layout' => 'block'
                    ]),

                ],
                'layout' => 'block'
            ]),
        ];
	}
}
