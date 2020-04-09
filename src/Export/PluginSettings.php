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
class PluginSettings implements \JsonSerializable
{
    /**
     * All Acf Settings in all Languages.
     *
     * @var array
     */
    private $acfSettings = [];

    /**
     * All Settings saved in the plugin settings table.
     *
     * @var array
     */
    private $pluginSettings = [];

    public function __construct()
    {
        $this->setAcfSettingsFromDatabase();
        $this->setPluginSettingsFromDatbase();
    }

    /**
     * Set Acf settings, in all languages.
     */
    private function setAcfSettingsFromDatabase(): void
    {
        $languages = PluginHelper::getActiveLanguages();
        if (!$languages || !is_iterable($languages)) {
            $this->setAcfSettings(Plugin::getAcfOptions());
            return;
        }

        $settings = [];
        collect($languages)->each(function ($language) use (&$settings) {
            $fieldNames = array_merge(
                AcfCookies::getFieldNames(),
                AcfSettings::getFieldNames()
            );
            $settings[$language] = collect($fieldNames)->map(function ($fieldname) use ($language) {
                return get_field($fieldname, 'options_' . $language);
            })->toArray();
        });

        $this->setAcfSettings($settings);
    }

    /**
     * Set Plugin Settings
     *
     * @param array $settings
     */
    public function setPluginSettings(array $settings): void
    {
        $this->pluginSettings = $settings;
    }

    /**
     * Set Acf Settings
     *
     * @param array $settings
     */
    public function setAcfSettings(array $settings): void
    {
        $this->acfSettings = $settings;
    }

    /*
     * Set historic plugin settings. The whole settings table.
     */
    private function setPluginSettingsFromDatbase(): void
    {
        $this->setPluginSettings((new SettingsTableAdapter())->getAllSettings());
    }

    /*
     * Serialize Class data for JSON use
     */
    public function jsonSerialize(): array
    {
        return [
            'acfSettings' => $this->acfSettings,
            'pluginSettings' => $this->pluginSettings
        ];
    }
}
