<?php

/**
 * AcfGroup interface File
 *
 * @package Towa\GdprPlugin
 * @author  Martin Welte
 * @copyright 2019 Towa
 */

namespace Towa\GdprPlugin;

/**
 * Interface AcfGroup
 *
 * @package Towa\GdprPlugin
 */
interface AcfGroup
{

	/**
	 * Function to register Acf Field Group
	 *
	 * @param string $page options page where the Acf Group should be displayed.
	 */
	public function register(string $page);

	/**
	 * Declare all used Fields within the group
	 *
	 * @return array
	 */
	public function build_fields(): array;
}
