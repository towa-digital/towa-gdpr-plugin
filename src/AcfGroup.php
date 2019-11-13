<?php
/**
 * Created by PhpStorm.
 * User: marti
 * Date: 09.11.2019
 * Time: 03:36
 */
namespace Towa\DsgvoPlugin;

interface AcfGroup{
    public function register(string $page);
	public function build_fields();
}
