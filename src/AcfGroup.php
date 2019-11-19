<?php
/**
 * Created by PhpStorm.
 * User: marti
 * Date: 09.11.2019
 * Time: 03:36
 */
namespace Towa\DsgvoPlugin;

interface AcfGroup{

	/**
	 * Function to register Acf Field Group
	 * @param string $page
	 */
  public function register(string $page);

	/**
	 * Declare all used Fields within the group
	 * @return array
	 */
	public function build_fields(): array;
}
