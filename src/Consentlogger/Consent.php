<?php

/**
 * Consent File
 *
 * @author       Martin Welte
 * @copyright    2019 Towa
 * @license      GPL-2.0+
 */

namespace Towa\GdprPlugin\Consentlogger;

use League\Csv\Reader;
use League\Csv\Writer;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Consent
 *
 * @package Towa\GdprPlugin\Consentlogger
 */
class Consent
{
	const LOG_DIR = WP_CONTENT_DIR . '/uploads/towa-gdpr/';

	/**
	 * @var \DateTime
	 */
	private $timestamp;

	/**
	 * @var string|null
	 */
	private $ip;

	/**
	 * @var string
	 */
	private $config;

	/**
	 * @var string
	 */
	private $hash;

	/**
	 * @var Request
	 */
	private $request;

	/**
	 * @var string
	 */
	private $url;

	/**
	 * Consent constructor.
	 *
	 * @param string $config
	 * @param string $hash
	 * @param string $url
	 * @throws \Exception
	 */
	public function __construct(string $config = '', string $hash = '', string $url = '')
	{
		$this->timestamp = new \DateTime();
		$this->request = Request::createFromGlobals();
		$this->ip = $this->request->getClientIp();
		$this->hash = $hash;
		$this->config = $config;
		$this->url = $url;
		return $this;
	}

	/**
	 * save consent to file
	 *
	 * @throws \League\Csv\CannotInsertRecord
	 */
	public function save(): void
	{
		if (!file_exists(self::LOG_DIR)) {
			$uploadpermissions = fileperms(WP_CONTENT_DIR . '/uploads/');
			mkdir(self::LOG_DIR, $uploadpermissions);
		}
		$filename = self::LOG_DIR . $this->timestamp->format('d-m-Y') . '.csv';
		$writemode = file_exists($filename) ? 'a' : 'w';
		$writer = Writer::createFromPath($filename, $writemode);
		$writer->insertOne($this->__toArray());
	}

	/**
	 * to Array function returns data of consent as array
	 *
	 * @return array
	 */
	public function __toArray(): array
	{
		return [
			'time' => $this->timestamp->format('Y-m-D H:i:s'),
			'ip' => $this->ip,
			'url' => $this->url,
			'cookies' => $this->config,
			'hash' => $this->hash,
		];
	}
}
