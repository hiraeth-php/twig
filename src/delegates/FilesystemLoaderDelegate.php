<?php

namespace Hiraeth\Twig;

use Hiraeth;
use Twig;

/**
 * A Hiraeth Delegate capable of creating a Twig\Loader\FilesystemLoader
 */
class FilesystemLoaderDelegate implements Hiraeth\Delegate
{
	/**
	 * {@inheritDoc}
	 */
	static public function getClass(): string
	{
		return Twig\Loader\FilesystemLoader::class;
	}


	/**
	 * {@inheritDoc}
	 */
	public function __invoke(Hiraeth\Application $app): object
	{
		$loader    = new Twig\Loader\FilesystemLoader();
		$templates = $app->getConfig('*', 'templates', [
			'priority' => 50,
			'paths'    => []
		]);

		uasort($templates, fn($a, $b) => $a['priority'] - $b['priority']);

		foreach ($templates as $config) {
			foreach ($config['paths'] as $namespace => $entries) {
				settype($entries, 'array');

				foreach ($entries as $entry) {
					$loader->addPath($app->getDirectory($entry)->getRealPath(), $namespace);
				}
			}
		}

		return $loader;
	}
}
