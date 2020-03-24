<?php

namespace Towa\GdprPlugin\Helper;

use Illuminate\Support\Str;

class PluginHelper
{
    /**
     * @return bool
     */
    public static function shouldRenewHash()
    {
        return (
            !isset($_POST['acf']['towa_gdpr_settings_hash'])
            || '' === $_POST['acf']['towa_gdpr_settings_hash']
            || isset($_POST['save_and_hash'])
        );
    }

    /**
     * @return bool
     */
    public static function isGdprPluginAdminScreen()
    {
        $screen = \get_current_screen();

        return ($screen && Str::contains($screen, 'towa-gdpr-plugin'));
    }

    /**
     * @return array
     */
    public static function getInternalIpsFromPostRequest()
    {
        if (
            isset($_POST['acf']['towa_gdpr_settings_towa_gdpr_internal'])
            && $internalIps = $_POST['acf']['towa_gdpr_settings_towa_gdpr_internal']
        ) {
            return $internalIps;
        }

        return [];
    }

    /**
     * @return string
     */
    public static function getCurrentLocale()
    {
        if (self::isWpmlActive()) {
            return ICL_LANGUAGE_CODE;
        }

        if (self::isPolylangActive()) {
            return pll_current_language('locale');
        }

        return '';
    }

    /**
     * @return array|string
     */
    public static function getActiveLanguages()
    {
        $languages = [];

        if (self::isWpmlActive()) {
            $languages = array_keys(apply_filters('wpml_active_languages', null, []));
        }

        if (self::isPolylangActive()) {
            $languages = pll_languages_list([
                'hide_empty' => 0,
                'fields' => 'locale'
            ]);
        }

        if ($languages && is_string($languages)) {
            $languages = [$languages];
        }

        return $languages;
    }

    /**
     * @return bool
     */
    public static function isWpmlActive()
    {
        return defined('ICL_LANGUAGE_CODE');
    }

    /**
     * @return bool
     */
    public static function isPolylangActive()
    {
        return function_exists('pll_current_language');
    }
}
