<?php

/**
 * Unit tests for Foo
 *
 * @package      Towa\GdprPlugin\Tests\Unit
 * @author       Martin Welte
 * @copyright    2019 Towa
 * @license      GPL-2.0+
 */

declare(strict_types=1);

namespace Towa\GdprPlugin\Tests\Unit;

use Towa\GdprPlugin\Foo as Testee;
use Towa\GdprPlugin\Tests\TestCase;

/**
 * Foo test case.
 */
class FooTest extends TestCase
{

	/**
	 * A single example test.
	 */
	public function testSample()
	{
		// Replace this with some actual testing code.
		static::assertTrue((new Testee())->is_true());
	}

	/**
	 * A single example test.
	 */
	public function testFoo()
	{
		// Replace this with some actual testing code.
		static::assertFalse(false);
	}

	/**
	 * A single example test.
	 */
	public function testBar()
	{
		// Replace this with some actual testing code.
		static::assertEquals('Foo::bar()', (new Testee())->bar());
	}
}
