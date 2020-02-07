<?php

/**
 * Settings Table Adapter File
 *
 * @author Martin Welte <martin.welte@towa.at>
 * @copyright 2020 Towa
 * @license GPL-2.0+
 */

namespace Towa\GdprPlugin;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Class SettingsTableAdapter
 *
 */
class SettingsTableAdapter
{
    private const TABLE_NAME = 'towa_gdpr_settings';

    /**
     * Datetime in mysql format
     *
     * @var string
     */
    private $datetime;

    /**
     * Settings in Json String
     *
     * @var string
     */
    private $settings;

    /**
     * Hash in MD5 format
     *
     * @var string
     */
    private $hash;

    /**
     * User id of current User
     *
     * @var int
     */
    private $user_id;

    /**
     * SettingsTableAdapter constructor.
     */
    public function __construct()
    {
        $this->setUpData();
    }

    /**
     * Setup Settings from Acf Data Array
     * @param array $plugindata
     */
    private function setSettings(array $plugindata): void
    {
        $settings = [];
        if (isset($plugindata['essential_group'])) {
            $settings['essential_group'] = $plugindata['essential_group'];
        }
        if (isset($plugindata['cookie_groups'])) {
            $settings['cookie_groups'] = $plugindata['cookie_groups'];
        }
        $this->settings = json_encode($settings);
    }

    /**
     * Set Hash from Acf Data Array
     * @param array $plugindata
     */
    private function setHash(array $plugindata)
    {
        if (isset($plugindata['hash'])) {
            $this->hash = $plugindata['hash'];
        }
    }

    /**
     * Set the Id of the current User
     */
    private function setUserId(): void
    {
        $this->user_id = get_current_user_id();
    }

    /**
     * Set Datetime to current time in mysql format
     */
    private function setDateTime(): void
    {
        $this->datetime = current_time('mysql');
    }

    /**
     * Set up Data to form new Record
     */
    private function setUpData()
    {
        $data = Plugin::getData();
        $this->setSettings($data);
        $this->setHash($data);
        $this->setUserId();
        $this->setDatetime();
    }

    /**
     * Get the full Tablename
     * @return string
     */
    private function getTableName(): string
    {
        global $wpdb;
        return $wpdb->prefix . self::TABLE_NAME;
    }

    /**
     * save the current Configuration to the database
     */
    public function save()
    {
        $insertData = get_object_vars($this);
        global $wpdb;
        $wpdb->insert($this->getTableName(), $insertData);
    }

    /**
     * Update Table Sructure if neccessary
     */
    public static function updateTableStructure(): void
    {
        global $wpdb;
        $tablename = $wpdb->prefix . self::TABLE_NAME;
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $tablename (
            id bigint(20) UNSIGNED AUTO_INCREMENT NOT NULL,
            datetime DATETIME NOT NULL,
            settings JSON NOT NULL,
            hash VARCHAR(100) NOT NULL,
            user_id BIGINT(20) UNSIGNED NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}
