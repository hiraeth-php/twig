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
	 * Get the instance of the class for which the delegate operates.
	 *
	 * @access public
	 * @param Hiraeth\Application $app The application instance for which the delegate operates
	 * @return Twig\Loader\FilesystemLoader Our filesystem loaer instance
	 */
	public function __invoke(Hiraeth\Application $app): object
	{
		$loader = new Twig\Loader\FilesystemLoader();
		$paths  = array_merge_recursive(...array_reverse(array_values(
			$app->getConfig('*', 'templates.paths', array())
		)));

		foreach ($paths as $namespace => $paths) {
			settype($paths, 'array');

			foreach ($paths as $path) {
				$loader->addPath($app->getDirectory($path)->getPathName(), $namespace);
			}
		}

		return $loader;
	}
}
