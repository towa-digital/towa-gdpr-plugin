<?php
/**
 * Unit tests for Foo
 *
 * @package      Towa\DsgvoPlugin\Tests\Unit
 * @author       Martin Welte
 * @copyright    2019 Towa
 * @license      GPL-2.0+
 */

declare( strict_types = 1 );

namespace Towa\DsgvoPlugin\Tests\Unit;

use Towa\DsgvoPlugin\Foo as Testee;
use Towa\DsgvoPlugin\Tests\TestCase;

/**
 * Foo test case.
 */
class FooTest extends TestCase {

	/**
	 * A single example test.
	 */
	public function test_sample() {
		// Replace this with some actual testing code.
		static::assertTrue( ( new Testee() )->is_true() );
	}

	/**
	 * A single example test.
	 */
	public function test_foo() {
		// Replace this with some actual testing code.
		static::assertFalse( false );
	}

	/**
	 * A single example test.
	 */
	public function test_bar() {
		// Replace this with some actual testing code.
		static::assertEquals( 'Foo::bar()', ( new Testee() )->bar() );
	}
}
