<?php

/**
 * Acf Settings File.
 *
 * @author  Martin Welte
 * @copyright Towa 2019
 */

namespace Towa\GdprPlugin\Acf;

use Towa\Acf\Fields\ColorPicker;
use Towa\Acf\Fields\Number;
use Towa\Acf\Fields\Relation;
use Towa\Acf\Fields\Repeater;
use Towa\Acf\Fields\Tab;
use Towa\Acf\Fields\Text;
use Towa\Acf\Fields\TrueFalse;
use Towa\Acf\Fields\Wysiwyg;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Class AcfSettings.
 */
class AcfSettings implements AcfGroupInterface
{
    /**
     * Name of AcfGroup.
     *
     * @var string
     */
    public $name = 'towa_gdpr_settings';

    /**
     * Register AcfGroup.
     *
     * @param string $page options page where the Acf Group should be displayed
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
     * Function build_fields returns Array of ACF Fields.
     */
    public function buildFields(): array
    {
        return [
            (new Tab($this->name, 'general_settings_tab', __('general Settings', 'towa-gdpr-plugin')))->build(),
            (new Text($this->name, 'tagmanager', __('Tagmanager ID', 'towa-gdpr-plugin')))->build(
                [
                    'instructions' => __('this will add the tagmanager installation script to the header of the page (be aware that it will not support the noscript iframe)', 'towa-gdpr-plugin'),
                ]
            ),
            (new Repeater($this->name, 'towa_gdpr_internal', __('Don\'t Track following IPs', 'towa-gdpr-plugin')))->build([
                'instructions' => 'IP4, IP6 or CDIR subnet (eg 172.17.0.0/17 or 2001:0DB8:0:CD30::1/60)',
                'required' => false,
                'sub_fields' => [
                    (new Text($this->name, 'towa_gdpr_ip', __('IP', 'towa-gdpr-plugin')))->build([
                        'required' => true,
                    ])
                ]
            ]),
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
            (new TrueFalse($this->name, 'activate_accordion', __('Akkordion aktivieren', 'towa-gdpr-plugin')) )-> build(),
            (new Text($this->name, 'accordion_text', __('Text für Akkordion', 'towa-gdpr-plugin')))->build([
                'conditional_logic' => [
                    [
                        'field' => $this->name . '_activate_accordion',
                        'operator' => '==',
                        'value' => 1,
                    ],
                ],
            ]),
            (new Tab($this->name, 'no_cookie_pages_tab', __('No Cookie Pages', 'towa-gdpr-plugin')))->build(),
            (new Relation($this->name, 'no_cookie_pages', __('Pages', 'towa-gdpr-plugin')))->build(
                [
                    'instructions' => __('All Pages/Custom Posts where the cookie notice will not be shown, and no tracking will happen. <strong>requires HTML cache refresh if changed.</strong>', 'towa-gdpr-plugin'),
                ]
            ),
        ];
    }

    /*
     * removes all ACF Settings Fields from Database
     */
    public static function deleteFields(): void
    {
        collect((new AcfSettings())->buildFields())->each(function ($field) {
            AcfUtility::deleteAcfFieldRecursively($field, 'option');
        });
    }
}
