<?php

/**
 * AcfGroup interface File.
 *
 * @author  Martin Welte
 * @copyright 2019 Towa
 */

namespace Towa\GdprPlugin;

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
}
