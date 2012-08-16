<?php
namespace Translation\Providers;

use Translation\Parsers\Parser;
/**
 *
 * @author martin.bazik
 */
abstract class Base implements Provider
{
	protected
		/** @var string */
		$lang,
			
		/** @var string */	
		$context,
			
		/** @var array<string|array> */
		$dictionary = array(),
		
		/** @var Parser */	
		$parser,
			
		/** @var array<string> */	
		$dirs
	;
	
	function __construct($dirs)
	{
		if(is_string($dirs))
		{
			$dirs = array($dirs);
		}
		$this->dirs = $dirs;
	}

	
	public function setLang($lang)
	{
		$this->lang = $lang;
		return $this;
	}
	
	public function setContext($context)
	{
		$this->context = $context;
		return $this;
	}
	
	public function translate($message, $count = NULL)
	{
		$this->loadDictionary();

		if(isset($this->dictionary[$message]))
		{
			return $this->dictionary[$message];
		}
		return $message;
	}
	
	protected function loadDictionary()
	{
	}
	
	public function getDictionary()
	{
		return $this->dictionary;
	}
}
