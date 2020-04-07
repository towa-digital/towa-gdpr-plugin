<?php

/**
 * Consent File.
 *
 * @author       Martin Welte
 * @copyright    2019 Towa
 * @license      GPL-2.0+
 */

namespace Towa\GdprPlugin\Consentlogger;

use League\Csv\Writer;
use Symfony\Component\HttpFoundation\Request;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
/**
 * Class Consent.
 */
class Consent
{
    const LOG_DIR = TOWA_GDPR_DATA . '/consents/';
    const TOWA_LOG_DIR_UPLOADPERMISSIONS = 0750;

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
     * @throws \Exception
     */
    public function __construct(string $config = '', string $hash = '', string $url = '')
    {
        $this->timestamp = new \DateTime();
        $this->request = Request::createFromGlobals();
        $this->request::setTrustedProxies(['127.0.0.1', 'REMOTE_ADDR'], Request::HEADER_X_FORWARDED_ALL);
        $this->ip = $this->request->getClientIp();
        $this->hash = $hash;
        $this->config = $config;
        $this->url = $url;

        return $this;
    }

    /**
     * save consent to file.
     *
     * @throws \League\Csv\CannotInsertRecord
     */
    public function save(): void
    {
        if (!file_exists(self::LOG_DIR)) {
            $this->createLogDirectory();
        }
        $filename = self::LOG_DIR . $this->timestamp->format('Y-m-d') . '.csv';
        $writemode = file_exists($filename) ? 'a' : 'w';
        $writer = Writer::createFromPath($filename, $writemode);
        $writer->insertOne($this->__toArray());
    }

    /**
     * to Array function returns data of consent as array.
     */
    public function __toArray(): array
    {
        return [
            'time' => $this->timestamp->format('Y-m-d H:i:s'),
            'ip' => $this->ip,
            'url' => $this->url,
            'cookies' => $this->config,
            'hash' => $this->hash,
        ];
    }

    /**
     * creates the Log directory
     */
    private function createLogDirectory(): void
    {
        mkdir(self::LOG_DIR, self::TOWA_LOG_DIR_UPLOADPERMISSIONS, true);
        @file_put_contents(self::LOG_DIR . '/index.php', "<?php \r\n// Silence is golden.");
        @file_put_contents(self::LOG_DIR . '/.htaccess', "Options -Indexes\r\nDeny from all");
    }
}
