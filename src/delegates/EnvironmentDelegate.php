<?php

namespace Hiraeth\Twig;

use Hiraeth;
use Twig;

/**
 *
 */
class EnvironmentDelegate implements Hiraeth\Delegate
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
		return Twig\Environment::class;
	}


	/**
	 * Get the instance of the class for which the delegate operates.
	 *
	 * @access public
	 * @param Hiraeth\Application $app The application instance for which the delegate operates
	 * @return Twig\Environment The instance of our logger
	 */
	public function __invoke(Hiraeth\Application $app): object
	{
		$options = $app->getConfig('packages/twig', 'twig', [
			'debug'   => $app->isDebugging(),
			'cache'   => 'storage/cache/templates',
			'charset' => 'utf-8'
		]);

		$environment = new Twig\Environment($app->get('Twig\Loader\LoaderInterface'), [
			'debug'            => $options['debug'],
			'strict_variables' => $options['strict'] ?? $options['debug'],
			'charset'          => $options['charset'],
			'cache'            => $app->getEnvironment('CACHING', TRUE)
				? $app->getDirectory($options['cache'], TRUE)->getPathname()
				: FALSE
		]);

		foreach ($app->getConfig('*', 'twig', array()) as $collection => $config) {
			//
			// Configure filters
			//

			foreach ($config['filters'] ?? [] as $name => $filter) {
				$options = $filter['options'] ?? array();
				$handler = !function_exists($filter['target'])
					? $app->get($filter['target'])
					: $filter['target'];


				$environment->addFilter(new Twig\TwigFilter($name, $handler, $options));
			}

			//
			// Configure functions
			//

			foreach ($config['functions'] ?? [] as $name => $function) {
				$handler = !function_exists($function['target'])
					? $app->get($function['target'])
					: $filter['target'];

				$environment->addFunction(new Twig\TwigFunction($name, $handler));
			}

			//
			// Configure globals
			//

			foreach ($config['globals'] ?? [] as $name => $class) {
				$environment->addGlobal($name, $app->get($class));
			}

			//
			// Configure extensions
			//

			foreach ($config['extensions'] ?? [] as $class) {
				$environment->addExtension($app->get($class));
			}
		}

		return $environment;
	}
}
