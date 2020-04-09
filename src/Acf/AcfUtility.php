<?php

namespace Towa\GdprPlugin\Acf;

// phpcs:disable PSR1.Files.SideEffects
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
// phpcs:enable

/**
 * Class AcfUtility
 * Helper Class for common ACF functions
 */
class AcfUtility
{
    /**
     * Delete ACF Field Recursively.
     *
     * If Field contains sub fields, they will be deleted too.
     */
    public static function deleteAcfFieldRecursively(
        array $field,
        string $postId = null,
        bool $isSubField = false
    ): void {
        if ($postId === null) {
            return;
        }

        if (! key_exists('name', $field)) {
            return;
        }

        global $wpdb;
        $tableprefix = $wpdb->prefix;
        $tableName = $tableprefix . 'post_meta';
        $columnName = $tableName . '.meta_key';
        $fieldName = sanitize_key($field['name']);
        $operator = '=';

        if ($postId === 'option' || $postId === 'options') {
            $tableName = $tableprefix . 'options';
            $columnName = $tableName . '.option_name';
            $fieldName = 'options_' . $fieldName;
        }
        if (key_exists('sub_fields', $field) && $field['type'] === 'repeater') {
            $fieldName = $fieldName . '%';
            $operator = 'LIKE';
        } elseif (key_exists('sub_fields', $field)) {
            collect($field['sub_fields'])->each(function ($subfield) use ($postId) {
                self::deleteAcfFieldRecursively($subfield, $postId);
            });
        }

        $sql = "DELETE FROM $tableName
                WHERE $columnName $operator '$fieldName'
                OR $columnName $operator '_$fieldName'";

        $wpdb->query($sql);
    }
}
