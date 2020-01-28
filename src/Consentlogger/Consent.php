<?php
/**
 * Created by PhpStorm.
 * User: marti
 * Date: 27.01.2020
 * Time: 16:29
 */

namespace Towa\GdprPlugin\Consentlogger;

use League\Csv\Reader;
use League\Csv\Writer;
class Consent
{
	const LOG_DIR = WP_CONTENT_DIR.'/uploads/towa-gdpr/';
	private $timestamp,$ip,$config,$hash,$via;

	public function __construct($config,$hash)
	{
		$this->timestamp = new \DateTime();
		$this->ip = $_SERVER['REMOTE_ADDR'];
		$this->via = $_SERVER['HTTP_X_FORWARDED_FOR'] ?: 0;
		$this->hash = $hash;
		$this->config = $config;
		return $this;
	}

	public function save(): void{
		$filename = self::LOG_DIR . $this->timestamp->format('d-m-Y') . '.csv';
		$writemode = 'w';
		if(file_exists($filename)){
			$writemode = 'a';
		}
		$writer = Writer::createFromPath($filename,$writemode);
		$writer->insertOne($this->__toArray());
	}

	public function __toArray(){
		return array(
			'ip' => $this->ip,
			'via' => $this->via,
			'cookies' => json_encode($this->config),
			'hash' => $this->hash,
			'time' => $this->timestamp->format('d.m.Y-H:i:s')
		);
		return (array) $this;
	}

}
