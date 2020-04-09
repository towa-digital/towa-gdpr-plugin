<?php

namespace Towa\GdprPlugin\Export;

use Towa\GdprPlugin\Acf\AcfCookies;
use Towa\GdprPlugin\Acf\AcfSettings;
use Towa\GdprPlugin\Helper\PluginHelper;
use Towa\GdprPlugin\Plugin;
use Towa\GdprPlugin\SettingsTableAdapter;

// phpcs:disable PSR1.Files.SideEffects
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
// phpcs:enable

/**
 * Gets all plugin settings as well as Acf settings in all Languages
 */
class PluginSettings
{
    /**
     * All Acf Settings in all Languages.
     *
     * @var array
     */
    public $acfSettings;

    /**
     * All Settings saved in the plugin settings table.
     *
     * @var array
     */
    public $pluginSettings;

    public function __construct()
    {
        $this->acfSettings = $this->getAcfSettings();
        $this->pluginSettings = $this->getPluginSettings();
    }

    /**
     * Get all Acf settings, in all languages.
     */
    private function getAcfSettings(): array
    {
        $languages = PluginHelper::getActiveLanguages();
        if (!$languages || !is_iterable($languages)) {
            return Plugin::getAcfOptions();
        }

        $settings = [];
        collect($languages)->each(function ($language) use (&$settings) {
            $fieldNames = array_merge(
                AcfCookies::getFieldNames(),
                AcfSettings::getFieldNames()
            );
            $settings = collect($fieldNames)->map(function ($fieldname) use ($language) {
                return get_field($fieldname, 'options_' . $language);
            })->toArray();
        });

        return $settings;
    }

    /*
     * Get historic plugin settings. The whole settings table.
     */
    private function getPluginSettings(): array
    {
        $settingsTable = new SettingsTableAdapter();
        return $settingsTable->getAllSettings();
    }
}
