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

		$environment = new Twig\Environment($app->get(Twig\Loader\LoaderInterface::class), [
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

				if ($handler = $this->resolve($app, $filter)) {
					$environment->addFilter(new Twig\TwigFilter($name, $handler, $options));
				}

			}

			//
			// Configure functions
			//

			foreach ($config['functions'] as $name => $function) {
				$options = $function['options'] ?? array();

				if ($handler = $this->resolve($app, $function)) {
					$environment->addFunction(new Twig\TwigFunction($name, $handler, $options));
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
	 * Resolve a configured target into a suitable PHP callable
	 *
	 * @param Hiraeth\Application $app
	 * @param mixed[] $config
	 */
	protected function resolve(Hiraeth\Application $app, array $config): ?callable
	{
		if (isset($config['target'])) {
			if (function_exists($config['target'])) {
				return $config['target'];
			}

			if ($app->has($config['target'])) {
				return $app->get($config['target']);
			}

			if (!($config['required'] ?? TRUE)) {
				return NULL;
			}

			throw new RuntimeException(sprintf(
				'Cannot add Twig funciton or filter "%s", not a function or class',
				$config['target']
			));
		}

		throw new RuntimeException('Cannot add twig function or filter with missing target');
	}
}
