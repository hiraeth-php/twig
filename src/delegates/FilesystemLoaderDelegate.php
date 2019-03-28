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
	 * Get the class for which the delegate operates.
	 *
	 * @static
	 * @access public
	 * @return string The class for which the delegate operates
	 */
	static public function getClass(): string
	{
		return Twig\Loader\FilesystemLoader::class;
	}


	/**
	 * Construct the delegate
	 *
	 * @access public
	 * @param Hiraeth\Application $app The Hiraeth application instance
	 * @return void
	 */
	public function __construct(Hiraeth\Application $app)
	{
		$this->app = $app;
	}


	/**
	 * Get the instance of the class for which the delegate operates.
	 *
	 * @access public
	 * @param Hiraeth\Broker $broker The dependency injector instance
	 * @return Twig\Loader\FilesystemLoader Our filesystem loaer instance
	 */
	public function __invoke(Hiraeth\Broker $broker): object
	{
		$loader = new Twig\Loader\FilesystemLoader();
		$paths  = array_merge(...array_reverse(array_values(
			$this->app->getConfig('*', 'templates.paths', array())
		)));

		foreach ($paths as $namespace => $path) {
			$loader->addPath($this->app->getDirectory($path), $namespace);
		}

		return $loader;
	}
}
