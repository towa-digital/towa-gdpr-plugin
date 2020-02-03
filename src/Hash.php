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
		$this->generateHash();
	}

	/**
	 * get the Hash
	 *
	 * @return string
	 */
	public function getHash(): string
	{
		return $this->hash;
	}

	/**
	 * generate a hash from the current Datetime
	 *
	 * @return void
	 * @throws \Exception
	 */
	private function generateHash(): void
	{
		$this->hash = md5((new DateTime('now'))->getTimestamp());
	}
}
