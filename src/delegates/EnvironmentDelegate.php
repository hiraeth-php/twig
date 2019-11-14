<?php

namespace Hiraeth\Twig;

use RuntimeException;
use Hiraeth;
use Twig;

/**
 * A Hiraeth Delegate capable of creating a Twig\Environment
 */
class EnvironmentDelegate implements Hiraeth\Delegate
{
	/**
	 * {@inheritDoc}
	 */
	static public function getClass(): string
	{
		return Twig\Environment::class;
	}


	/**
	 * {@inheritDoc}
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

		$defaults = [
			'extensions' => array(),
			'filters'    => array(),
			'functions'  => array(),
			'globals'    => array(),
		];

		foreach ($app->getConfig('*', 'twig', $defaults) as $path => $config) {

			//
			// Configure extensions
			//

			foreach ($config['extensions'] as $class) {
				$environment->addExtension($app->get($class));
			}

			//
			// Configure filters
			//

			foreach ($config['filters'] as $name => $filter) {
				$options = $filter['options'] ?? array();

				if ($handler = $this->resolve($app, $function)) {
					$environment->addFilter(new Twig\TwigFilter($name, $handler, $options));
				}

			}

			//
			// Configure functions
			//

			foreach ($config['functions'] as $name => $function) {
				if ($handler = $this->resolve($app, $function)) {
					$environment->addFunction(new Twig\TwigFunction($name, $handler));
				}
			}

			//
			// Configure globals
			//

			foreach ($config['globals'] as $name => $class) {
				$environment->addGlobal($name, $app->get($class));
			}

		}

		return $environment;
	}


	/**
	 *
	 */
	protected function resolve($app, $config)
	{
		if (isset($function['target'])) {
			if (function_exists($function['target'])) {
				return $function['target'];
			}

			if ($app->has($function['target'])) {
				return $app->get($function['target']);
			}

			if (!$function['required'] && TRUE) {
				return NULL;
			}

			throw new RuntimeException(sprintf(
				'Cannot add Twig funciton or filter "%s", not a function or class',
				$function['target']
			));
		}

		throw new RuntimeException('Cannot add twig function or filter with missing target');
	}
}
