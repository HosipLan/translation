<?php

namespace Mazagran\Translation;

use Mazagran\Translation\Extraction\Filters;
use Mazagran\Translation\Extraction\Context;

/**
 * Test class for GettextContext_Filters_NetteLatteFilter.
 * Generated by PHPUnit on 2010-12-15 at 21:59:47.
 */
class NetteLatteFilterTest extends FilterTest
{

	protected function setUp()
	{
		$this->object = new Filters\NetteLatte;
		$this->file = __DIR__ . '/data/filesToScan/default.latte';
	}

	public function testFunctionCallWithVariables()
	{
		$messages = $this->object->extract($this->file);

		$this->assertNotContains(array(
			Context::LINE => 7,
			Context::SINGULAR => '$foo'
				), $messages);

		$this->assertNotContains(array(
			Context::LINE => 8,
			Context::SINGULAR => '$bar',
			Context::CONTEXT => 'context'
				), $messages);

		$this->assertNotContains(array(
			Context::LINE => 9,
			Context::SINGULAR => 'I see %d little indian!',
			Context::PLURAL => 'I see %d little indians!',
			Context::CONTEXT => '$baz'
				), $messages);
	}

	public function testConstantsArrayMethodsAndFunctions()
	{
		$messages = $this->object->extract(__DIR__ . '/data/filesToScan/test.latte');

		$this->assertContains(array(
			Context::LINE => 1,
			Context::SINGULAR => 'Testovaci retezec'
				), $messages);

		$this->assertNotContains(array(
			Context::LINE => 3,
			Context::SINGULAR => '69'
				), $messages);

		$this->assertNotContains(array(
			Context::LINE => 4,
			Context::SINGULAR => 'CONSTANT'
				), $messages);

		$this->assertNotContains(array(
			Context::LINE => 5,
			Context::SINGULAR => 'Class::CONSTANT'
				), $messages);

		$this->assertNotContains(array(
			Context::LINE => 6,
			Context::SINGULAR => 'Class::method()'
				), $messages);

		$this->assertNotContains(array(
			Context::LINE => 7,
			Context::SINGULAR => '$array[0]'
				), $messages);

		$this->assertNotContains(array(
			Context::LINE => 8,
			Context::SINGULAR => '$varFunc()'
				), $messages);

		$this->assertNotContains(array(
			Context::LINE => 9,
			Context::SINGULAR => '$object->method()'
				), $messages);

		$this->assertNotContains(array(
			Context::LINE => 10,
			Context::SINGULAR => 'function()'
				), $messages);

		$this->assertNotContains(array(
			Context::LINE => 11,
			Context::SINGULAR => 'function()->fluent()'
				), $messages);

		$this->assertNotContains(array(
			Context::LINE => 12,
			Context::SINGULAR => 'Class::$var[0][\'key\']($arg)->method()->method()'
				), $messages);
	}

}
