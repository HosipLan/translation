<?php
namespace Mazagran\Translation\DI;
use Nette\Config\Configurator;
use Nette\DI\Container;
use Nette\Config\Compiler;
/**
 * Extension
 *
 * @author martin.bazik
 */
class Extension extends \Nette\Config\CompilerExtension
{
	public 
		/** @var array */	
		$defaults = array(
			'provider' => 'gettext',
			'scanFile' => '%appDir%',
			'localizationFolder' => '%appDir%/l10n/',
			'meta' => array(
				'Project-Id-Version' => '',
				'PO-Revision-Date' => '',
				'Last-Translator' => '',
				'Language-Team' => ''
			)
		)
	;

	private static $providerMap = array(
		'gettext' => '\Translation\Providers\Gettext',
		'neon' => '\Translation\Providers\Neon',
		'ini' => '\Translation\Providers\Ini',
	);
	
	/**
	 * Processes configuration data
	 *
	 * @return void
	 */
	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		
		$config = $this->getConfig($this->defaults, true);
		
		$builder->addDefinition($this->prefix('provider'))
			->setFactory('Mazagran\Translation\DI\Extension::createProvider', array($config));
		
		$builder->addDefinition($this->prefix('translator'))
			->setFactory('Mazagran\Translation\DI\Extension::createTranslator', array('@container'));
		
		$builder->addDefinition($this->prefix('consoleCommandExtract'))
			->setFactory('Mazagran\Translation\DI\Extension::createConsoleCommandExtract', array($config))
			->addTag('consoleCommand');
		
		$builder->addDefinition($this->prefix('consoleCommandCreateLangFile'))
			->setFactory('Mazagran\Translation\DI\Extension::createConsoleCommandCreateLangFile', array($config))
			->addTag('consoleCommand');
		
		$builder->addDefinition($this->prefix('consoleCommandUpdate'))
			->setFactory('Mazagran\Translation\DI\Extension::createConsoleCommandUpdate', array($config))
			->addTag('consoleCommand');
	}
	
	public static function createConsoleCommandExtract($config)
	{
		$command = new \Translation\Console\Commands\Extract;
		$command->setExtractDirs($config['scanFile'])->setOutputFolder($config['localizationFolder']);
		return $command;
	}
	
	public static function createConsoleCommandCreateLangFile($config)
	{
		$command = new \Translation\Console\Commands\CreateLangFile;
		$command->setOutputFolder($config['localizationFolder']);
		return $command;
	}
	
	public static function createConsoleCommandUpdate($config)
	{
		$command = new \Translation\Console\Commands\Update;
		$command->setExtractDirs($config['scanFile'])->setOutputFolder($config['localizationFolder']);
		return $command;
	}
	
	public static function createProvider($config)
	{
		$providerClass = self::$providerMap[$config['provider']];
		return new $providerClass($config['localizationFolder']);
	}
	
	public static function createTranslator(Container $container)
	{
		return new \Translation\Translator($container->translation->provider);
	}
	
	public static function register(Configurator $configurator)
	{
		$configurator->onCompile[] = function (Configurator $configurator, Compiler $compiler) {
			$compiler->addExtension('translation', new Extension);
		};
	}
	
}