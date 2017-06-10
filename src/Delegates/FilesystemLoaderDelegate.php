<?php

namespace Hiraeth\Twig;

use Hiraeth;
use Twig;

/**
 *
 */
class FilesystemLoaderDelegate implements Hiraeth\Delegate
{
	/**
	 * The Hiraeth application instance
	 *
	 * @access protected
	 * @var Hiraeth\Application
	 */
	protected $app = NULL;


	/**
	 * The Hiraeth configuration instance
	 *
	 * @access protected
	 * @var Hiraeth\Configuration
	 */
	protected $config = NULL;


	/**
	 * Get the class for which the delegate operates.
	 *
	 * @static
	 * @access public
	 * @return string The class for which the delegate operates
	 */
	static public function getClass()
	{
		return 'Twig\Loader\FilesystemLoader';
	}


	/**
	 * Get the interfaces for which the delegate provides a class.
	 *
	 * @static
	 * @access public
	 * @return array A list of interfaces for which the delegate provides a class
	 */
	static public function getInterfaces()
	{
		return [
			'Twig\Loader\LoaderInterface',
			'Twig_LoaderInterface'
		];
	}


	/**
	 * Construct the delegate
	 *
	 * @access public
	 * @param Hiraeth\Application $app The Hiraeth application instance
	 * @param Hiraeth\Configuration $config The Hiraeth configuration instance
	 * @return void
	 */
	public function __construct(Hiraeth\Application $app, Hiraeth\Configuration $config)
	{
		$this->app    = $app;
		$this->config = $config;
	}


	/**
	 * Get the instance of the class for which the delegate operates.
	 *
	 * @access public
	 * @param Hiraeth\Broker $broker The dependency injector instance
	 * @return Twig\Loader\FilesystemLoader Our filesystem loaer instance
	 */
	public function __invoke(Hiraeth\Broker $broker)
	{
		$loader = new Twig\Loader\FilesystemLoader();

		foreach ($this->config->get('*', 'twig.paths', array()) as $config => $paths) {
			foreach ($paths as $namespace => $path) {
				$loader->addPath($this->app->getDirectory($path), $namespace);
			}
		}

		return $loader;
	}
}
