<?php

namespace Towa\GdprPlugin\Acf;

/**
 * Interface AcfGroup.
 */
interface AcfGroupInterface
{
    /**
     * Function to register Acf Field Group.
     *
     * @param string $page options page where the Acf Group should be displayed
     */
    public function register(string $page);

    /**
     * Declare all used Fields within the group.
     */
    public function buildFields(): array;

    /**
     * deletes all fields of group
     */
    public static function deleteFields(): void;

    /**
     * get all field names of group
     */
    public static function getFieldNames(): array;
}
