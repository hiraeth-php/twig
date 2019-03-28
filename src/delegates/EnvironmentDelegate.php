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
		return Twig\Environment::class;
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
	 * @return Twig\Environment The instance of our logger
	 */
	public function __invoke(Hiraeth\Broker $broker): object
	{
		$options = $this->app->getConfig('packages/twig', 'twig', [
			'debug'   => (bool) $this->app->getEnvironment('DEBUG'),
			'strict'  => (bool) $this->app->getEnvironment('DEBUG'),
			'cache'   => 'storage/cache/templates',
			'charset' => 'utf-8'

		]);

		$environment = new Twig\Environment($broker->make('Twig\Loader\LoaderInterface'), [
			'debug'            => $options['debug'],
			'strict_variables' => $options['strict'],
			'charset'          => $options['charset'],
			'cache_path'       => $this-app->getEnvironment('CACHING', TRUE)
				? $this->app->getDirectory($options['cache'], TRUE)->getPathname()
				: FALSE
		]);

		foreach ($this->app->getConfig('*', 'twig', array()) as $collection => $config) {
			//
			// Configure filters
			//

			foreach ($config['filters'] ?? [] as $name => $filter) {
				$options = $filter['options'] ?? array();
				$handler = !function_exists($filter['target'])
					? $broker->make($filter['target'])
					: $filter['target'];


				$environment->addFilter(new Twig\TwigFilter($name, $handler, $options));
			}

			//
			// Configure functions
			//

			foreach ($config['functions'] ?? [] as $name => $function) {
				$handler = !function_exists($function['target'])
					? $broker->make($function['target'])
					: $filter['target'];

				$environment->addFunction(new Twig\TwigFunction($name, $handler));
			}

			//
			// Configure globals
			//

			foreach ($config['globals'] ?? [] as $name => $class) {
				$environment->addGlobal($name, $broker->make($class));
			}

			//
			// Configure extensions
			//

			foreach ($config['extensions'] ?? [] as $class) {
				$environment->addExtension($broker->make($class));
			}
		}

		return $environment;
	}
}
