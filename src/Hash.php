<?php

namespace Towa\GdprPlugin;

use DateTime;

// phpcs:disable PSR1.Files.SideEffects
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
// phpcs:enable

/**
 * Hash class.
 *
 * Class to generate Hashes based on the current date
 *
 * @author Martin Welte <martin.welte@towa.at>
 */
class Hash
{
    /**
     * hash value.
     *
     * @var string
     */
    private $hash;

    /**
     * Instantiating the Hash.
     */
    public function __construct()
    {
        $this->generateHash();
    }

    /**
     * get the Hash.
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * generate a hash from the current Datetime.
     *
     * @throws \Exception
     */
    private function generateHash(): void
    {
        $this->hash = md5((string)(new DateTime('now'))->getTimestamp());
    }
}
