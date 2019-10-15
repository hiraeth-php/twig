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
		$loader    = new Twig\Loader\FilesystemLoader();
		$templates = $app->getConfig('*', 'templates', array());

		uasort($templates, function($a, $b) {
			$a_priority = $a['priority'] ?? 50;
			$b_priority = $b['priority'] ?? 50;
		});

		foreach ($templates as $config) {
			$paths = $config['paths'] ?? array();

			foreach ($paths as $namespace => $entries) {
				settype($entries, 'array');

				foreach ($entries as $entry) {
					$loader->addPath(
						$app->getDirectory($entry)->getRealPath(),
						$namespace
					);
				}
			}
		}

		return $loader;
	}
}
