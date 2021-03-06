<?php

namespace Mazagran\Translation;

use Mazagran\Translation\Extraction\Context;
use Mazagran\Translation\Extraction\Filters;

/**
 * Test class for PHPFilter.
 * Generated by PHPUnit on 2010-12-15 at 21:59:45.
 */
class PHPFilterTest extends FilterTest
{

	protected function setUp()
	{
		$this->object = new Filters\PHP;
		$this->object->addFunction('addRule', 2);
		$this->file = __DIR__ . '/data/filesToScan/default.php';
	}

	public function testFunctionCallWithVariables()
	{
		$messages = $this->object->extract($this->file);

		$this->assertNotContains(array(
			Context::LINE => 7
				), $messages);

		$this->assertNotContains(array(
			Context::LINE => 8,
			Context::CONTEXT => 'context'
				), $messages);

		$this->assertNotContains(array(
			Context::LINE => 9,
			Context::SINGULAR => 'I see %d little indian!',
			Context::PLURAL => 'I see %d little indians!'
				), $messages);
	}

	public function testNestedFunctions()
	{
		$messages = $this->object->extract($this->file);

		$this->assertNotContains(array(
			Context::LINE => 11,
			Context::SINGULAR => 'Some string.'
				), $messages);

		$this->assertContains(array(
			Context::LINE => 12,
			Context::SINGULAR => 'Nested function.'
				), $messages);

		$this->assertContains(array(
			Context::LINE => 13,
			Context::SINGULAR => 'Nested function 2.',
			Context::CONTEXT => 'context'
				), $messages);
		$this->assertNotContains(array(
			Context::LINE => 13,
			Context::SINGULAR => 'context'
				), $messages);

		$this->assertContains(array(
			Context::LINE => 14,
			Context::SINGULAR => "%d meeting wasn't imported.",
			Context::PLURAL => "%d meetings weren't importeded."
				), $messages);
		$this->assertNotContains(array(
			Context::LINE => 14,
			Context::SINGULAR => "%d meeting wasn't imported."
				), $messages);

		$this->assertContains(array(
			Context::LINE => 17,
			Context::SINGULAR => "Please provide a text 2."
				), $messages);
		$this->assertContains(array(
			Context::LINE => 18,
			Context::SINGULAR => "Please provide a text 3."
				), $messages);
	}

	public function testConstantAsParameter()
	{
		$messages = $this->object->extract($this->file);

		$this->assertContains(array(
			Context::LINE => 16,
			Context::SINGULAR => "Please provide a text."
				), $messages);
	}

	public function testMessageWithNewlines()
	{
		$messages = $this->object->extract($this->file);

		
		
		$this->assertContains(array(
			Context::LINE => 22,
			Context::SINGULAR => "A\nmessage!"
				), $messages);
	}

	public function testArrayAsParameter()
	{
		$this->object->addFunction('addConfirmer', 3);
		$messages = $this->object->extract($this->file);

		$this->assertContains(array(
			Context::LINE => 25,
			Context::SINGULAR => "Really delete?"
				), $messages);
	}

	/**
	 * @group bug5
	 */
	public function testArrayWithTranslationsAsParameter()
	{
		$this->object->addFunction('addSelect', 3);
		$messages = $this->object->extract($this->file);

		$this->assertContains(array(
			Context::LINE => 26,
			Context::SINGULAR => "item 1"
				), $messages);
		$this->assertContains(array(
			Context::LINE => 26,
			Context::SINGULAR => "item 2"
				), $messages);
	}

	/**
	 * @group bug3
	 */
	public function testMultipleMessagesFromSingleFunction()
	{
		$this->object->addFunction('bar', 1);
		$this->object->addFunction('bar', 2);
		$messages = $this->object->extract($this->file);

		$this->assertContains(array(
			Context::LINE => 30,
			Context::SINGULAR => "Value A"
				), $messages);
		$this->assertContains(array(
			Context::LINE => 30,
			Context::SINGULAR => "Value B"
				), $messages);
	}

	public function testCallable()
	{
		$this->object->extract(__DIR__ . '/data/filesToScan/callable.php');
	}

	public function testStaticFunctions()
	{
		$messages = $this->object->extract($this->file);

		$this->assertContains(array(
			Context::LINE => 31,
			Context::SINGULAR => "Static function"
				), $messages);
	}

}
