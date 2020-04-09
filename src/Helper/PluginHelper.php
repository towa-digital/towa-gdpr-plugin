<?php

namespace Towa\GdprPlugin\Helper;

use Illuminate\Support\Str;

class PluginHelper
{
    /**
     * Returns if the hash should be regenerated.
     */
    public static function shouldRenewHash(): bool
    {
        return (
            !isset($_POST['acf']['towa_gdpr_settings_hash'])
            || '' === $_POST['acf']['towa_gdpr_settings_hash']
            || isset($_POST['save_and_hash'])
        );
    }

    /**
     * Returns if the export settings button was hit.
     */
    public static function shouldExport(): bool
    {
        return isset($_POST['export_settings']);
    }

    /**
     * Is the current screen, the towa-gdpr screen.
     */
    public static function isGdprPluginAdminScreen(): bool
    {
        $screen = \get_current_screen();

        return ($screen && Str::contains($screen->id, 'towa-gdpr-plugin'));
    }

    /**
     * Get IPs from POST.
     */
    public static function getInternalIpsFromPostRequest(): array
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
     * Get current language code
     */
    public static function getCurrentLocale(): string
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
     * Get the currently active Language
     */
    public static function getActiveLanguages(): array
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
     * Returns true if Wpml is active.
     */
    public static function isWpmlActive(): bool
    {
        return defined('ICL_LANGUAGE_CODE');
    }

    /**
     * Returns true if Polylang is active.
     */
    public static function isPolylangActive()
    {
        return function_exists('pll_current_language');
    }

    /**
     * Get towa-gdpr Data Path.
     *
     * @throws \Exception
     */
    public static function getDataPath(): string
    {
        $uploadPath = \wp_get_upload_dir();
        if (isset($uploadPath['basedir'])) {
            return implode('/', [$uploadPath['basedir'], 'towa-gdpr']);
        }
        throw new \Exception("Can't find upload directory");
    }
}
