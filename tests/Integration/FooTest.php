<?php

/**
 * Integration tests for Foo
 *
 * @package      Towa\GdprPlugin\Tests\Integration
 * @author       Martin Welte
 * @copyright    2019 Towa
 * @license      GPL-2.0+
 */

declare(strict_types=1);

namespace Towa\GdprPlugin\Tests\Integration;

use Towa\GdprPlugin\Foo as Testee;
use WP_UnitTestCase;

/**
 * Foo test case.
 */
class FooTest extends WP_UnitTestCase
{
	/**
	 * A single example test.
	 */
	public function testFoo()
	{
		// Replace this with some actual integration testing code.
		static::assertTrue((new Testee())->is_true());
	}
}
