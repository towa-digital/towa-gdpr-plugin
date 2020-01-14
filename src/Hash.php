<?php

/**
 * Hash class file
 *
 * @package Towa\GdprPlugin
 * @author Martin Welte <martin.welte@towa.at>
 * @copyright 2019 Towa
 * @license GPL-2.0+
 */

namespace Towa\GdprPlugin;

use DateTime;

/**
 * Hash class
 *
 * @package Towa\GdprPlugin
 * @author Martin Welte <martin.welte@towa.at>
 */
class Hash
{

	/**
	 * hash value
	 *
	 * @var string
	 */
	private $hash;

	/**
	 * Instantiating the Hash
	 */
	public function __construct()
	{
		$this->generate_hash();
	}

	/**
	 * get the Hash
	 *
	 * @return string
	 */
	public function get_hash(): string
	{
		return $this->hash;
	}

	/**
	 * generate a hash from the current Datetime
	 *
	 * @return void
	 */
	public function generate_hash(): void
	{
		$this->hash = md5((new DateTime('now'))->getTimestamp());
	}

	/**
	 * set the Hash
	 *
	 * @param string $hash
	 * @return void
	 */
	public function setHash(string $hash): void
	{
		$this->hash = $hash;
	}
}
