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
		return 'Twig\Environment';
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
			'Twig_Environment'
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
	 * @return Twig\Environment The instance of our logger
	 */
	public function __invoke(Hiraeth\Broker $broker)
	{
		$config = [
			'debug'   => (bool) $this->app->getEnvironment('DEBUG'),
			'charset' => $this->config->get('twig', 'charset', 'utf-8')
		];

		if ($this->app->getEnvironment('CACHING', TRUE)) {
			$config['cache'] = $this->app->getDirectory(
				$this->config->get('twig', 'cache_path', 'writable/cache/twig'),
				TRUE
			);

		} else {
			$config['cache'] = FALSE;
		}

		if ($this->config->get('twig', 'strict', NULL) !== NULL) {
			$config['strict_variables'] = $this->config->get('twig', 'strict', NULL);
		} else {
			$config['strict_variables'] = !$config['debug'];
		}

		$loader_class = $this->config->get('twig', 'loader', 'Twig\Loader\LoaderInterface');
		$environment  = new Twig\Environment($broker->make($loader_class), $config);

		foreach (array_keys($this->config->get('*', 'twig', array())) as $config) {
			//
			// Configure filters
			///

			$filters = $this->config->get($config, 'twig.filters', array());

			foreach ($filters as $name => $filter) {
				if (function_exists($filter['target'])) {
					$filter = new Twig\TwigFilter($name, $filter['target'], $filter['options'] ?? array());
				} else {
					$handler = $broker->make($filter['target']);
					$filter  = new Twig\TwigFilter($name, $handler, $filter['options'] ?? array());
				}

				$environment->addFilter($filter);
			}

			//
			// Configure functions
			//

			$functions = $this->config->get($config, 'twig.functions', array());

			foreach ($functions as $name => $function) {
				if (function_exists($function['target'])) {
					$function = new Twig\TwigFunction($name, $function['target']);
				} else {
					$handler  = $broker->make($function['target']);
					$function = new Twig\TwigFunction($name, $handler);
				}

				$environment->addFunction($function);
			}

			//
			// Configure globals
			//

			$globals = $this->config->get($config, 'twig.globals', array());

			foreach ($globals as $name => $class) {
				$environment->addGlobal($name, $broker->make($class));
			}

			//
			// Configure extensions
			//

			$extensions = $this->config->get($config, 'twig.extensions', array());

			foreach ($extensions as $class) {
				$environment->addExtension($broker->make($class));
			}
		}

		return $environment;
	}
}
