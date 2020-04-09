<?php

namespace Towa\GdprPlugin;

// phpcs:disable PSR1.Files.SideEffects
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
// phpcs:enable

/**
 * Class SettingsTableAdapter
 *
 */
final class SettingsTableAdapter
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
    private $userId;

    /**
     * SettingsTableAdapter constructor.
     */
    public function __construct()
    {
        $data = Plugin::getData();
        $this->setSettings($data);
        $this->setHash($data);
        $this->setUserId();
        $this->setDatetime();
    }

    /**
     * get the WordPress Database Object
     *
     * @return \wpdb
     */
    public static function getDb(): \wpdb
    {
        global $wpdb;

        return $wpdb;
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
        $this->settings = (string)json_encode($settings);
    }

    /**
     * Set Hash from Acf Data Array
     * @param array $plugindata
     */
    private function setHash(array $plugindata)
    {
        if (isset($plugindata['hash'])) {
            $this->hash = $plugindata['hash'];
        } else {
            new \Exception('Hash not defined');
        }
    }

    /**
     * Set the Id of the current User
     */
    private function setUserId(): void
    {
        $this->userId = get_current_user_id();
    }

    /**
     * Set Datetime to current time in mysql format
     */
    private function setDateTime(): void
    {
        $this->datetime = (string)current_time('mysql');
    }

    /**
     * Get the full Tablename
     * @return string
     */
    public static function getTableName(): string
    {
        global $wpdb;
        return $wpdb->prefix . self::TABLE_NAME;
    }

    /**
     * save the current Configuration to the database
     */
    public function save()
    {
        self::updateTableStructure();
        $insertData = get_object_vars($this);
        $wpdb = self::getDb();
        $wpdb->insert(self::getTableName(), $insertData);
    }

    /**
     * Update Table Sructure if neccessary
     */
    public static function updateTableStructure(): void
    {
        $wpdb = self::getDb();
        $tablename = self::getTableName();
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $tablename (
            id bigint(20) UNSIGNED AUTO_INCREMENT NOT NULL,
            datetime DATETIME NOT NULL,
            settings TEXT NOT NULL,
            hash VARCHAR(100) NOT NULL,
            userId BIGINT(20) UNSIGNED NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    /**
     * Destroy Settings Table
     */
    public static function destroyTable(): void
    {
        if (!defined('WP_UNINSTALL_PLUGIN')) {
            exit;
        }

        $wpdb = self::getDb();
        $tablename = self::getTableName();
        $sql = "DROP TABLE IF EXISTS $tablename";
        $wpdb->query($sql);
    }
}
