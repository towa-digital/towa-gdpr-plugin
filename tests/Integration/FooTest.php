<?php
/**
 * Integration tests for Foo
 *
 * @package      Towa\DsgvoPlugin\Tests\Integration
 * @author       Martin Welte
 * @copyright    2019 Towa
 * @license      GPL-2.0+
 */

declare( strict_types = 1 );

namespace Towa\DsgvoPlugin\Tests\Integration;

use Towa\DsgvoPlugin\Foo as Testee;
use WP_UnitTestCase;

/**
 * Foo test case.
 */
class FooTest extends WP_UnitTestCase {
	/**
	 * A single example test.
	 */
	public function test_foo() {
		// Replace this with some actual integration testing code.
		static::assertTrue( ( new Testee() )->is_true() );
	}
}
